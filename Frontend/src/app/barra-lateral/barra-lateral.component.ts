import { Component, OnInit, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; 
import { CalendarioComponent } from '../calendario/calendario.component';
import { UserService } from '../services/user.service';
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { debounceTime, distinctUntilChanged, switchMap } from 'rxjs/operators';// <-- Importando operadores de RxJS parra tema de barra de buscar usuarios
import { Subject } from 'rxjs';
@Component({
  selector: 'app-barra-lateral',
  standalone: true,
  imports: [FormsModule, CommonModule, CalendarioComponent],
  templateUrl: './barra-lateral.component.html',
  styleUrl: './barra-lateral.component.scss'
})
export class BarraLateralComponent implements OnInit {
  private userService = inject(UserService); // ✅ usando inject()
  sessionService = inject(SessionService);
  busqueda = '';
  amigos: { nick: string, rutaAvatar: string }[] = [];
  solicitudes: { solicitante: string, recibidor: string, aceptada: number, rutaAvatar?: string }[] = [];
   usuarios: { nick: string, rutaAvatar?: string }[] = [];//Usado para El buscador de usuarios
  private busquedaSubject = new Subject<string>();
  ides: { ide: string, image: string }[] = []; // Para mostrar los IDEs con imagen

  ngOnInit(): void {
   // Escuchar cambios en búsqueda con debounce
    this.busquedaSubject.pipe(
      debounceTime(300),
      distinctUntilChanged(),
      switchMap(texto => this.userService.buscarUsuarios(texto))
    ).subscribe(resultados => {
      // Resultados vienen solo con nick
      this.usuarios = resultados;

      // Para cada usuario, buscar su avatar
      this.usuarios.forEach(u => {
        this.userService.getRutaAvatar(u.nick).subscribe(res => {
          u.rutaAvatar = res.ruta;
        });
      });
    });
    this.recargarSolicitudes();
    // Cargar amigos y solicitudes de amistad al iniciar el componente
      this.userService.getSolicitudesDeAmistad().subscribe(res => {
      this.solicitudes = res.Solicitudes ?? [];
      console.log('Solicitudes:', this.solicitudes);
    });
     this.userService.getAmigos().subscribe(res => {
      const nicks: string[] = res.amigos ?? [];
      this.amigos = [];
      nicks.forEach((nick: string) => {
        this.userService.getRutaAvatar(nick).subscribe(avatarRes => {
          this.amigos.push({ nick, rutaAvatar: avatarRes.ruta });
        });
      });
    });
  }
  onBuscarCambio(texto: string) {
    if (!texto.trim()) {
      this.usuarios = [];
      return;
    }

    this.busquedaSubject.next(texto);
  }

  aceptarSolicitud(solicitud: any) {
    const recibidor = this.sessionService.getUsuario();
    if (!recibidor) {
      alert('Debes iniciar sesión para aceptar solicitudes.');
      return;
    }
    console.log('Enviando:', { solicitante: solicitud.solicitante, recibidor });
    this.userService.aceptarSolicitud({
      solicitante: solicitud.solicitante,
      recibidor: recibidor
    }).subscribe(() => {
      this.recargarSolicitudes();
      this.recargarAmigos();
    });
  }
  recargarAmigos() {
    this.userService.getAmigos().subscribe(res => {
      const nicks: string[] = res.amigos ?? [];
      const nuevosAmigos: { nick: string, rutaAvatar: string }[] = [];

      nicks.forEach(nick => {
        this.userService.getRutaAvatar(nick).subscribe(avatarRes => {
          nuevosAmigos.push({ nick, rutaAvatar: avatarRes.ruta });

          // Solo reemplaza cuando se hayan agregado todos
          if (nuevosAmigos.length === nicks.length) {
            this.amigos = nuevosAmigos;
          }
        });
      });

      if (nicks.length === 0) {
        this.amigos = []; // Si no hay amigos, limpia
      }
    });
  }
  rechazarSolicitud(solicitud: any) {
   const recibidor = this.sessionService.getUsuario();
    if (!recibidor) {
      alert('Debes iniciar sesión para aceptar solicitudes.');
      return;
    }
    console.log('Enviando:', { solicitante: solicitud.solicitante, recibidor });
    this.userService.rechazarSolicitud({
      solicitante: solicitud.solicitante,
      recibidor: recibidor
    }).subscribe(() => {
      this.recargarSolicitudes();
      this.recargarAmigos();
    });
  }

recargarSolicitudes() {
  this.userService.getSolicitudesDeAmistad().subscribe(res => {
    const solicitudesRaw = res.Solicitudes ?? [];

    const nuevasSolicitudes: {
      solicitante: string;
      recibidor: string;
      aceptada: number;
      rutaAvatar?: string;
    }[] = [];

    solicitudesRaw.forEach((solicitud: any) => {
      this.userService.getRutaAvatar(solicitud.solicitante).subscribe(avatarRes => {
        nuevasSolicitudes.push({
          ...solicitud,
          rutaAvatar: avatarRes.ruta
        });

        // Cuando ya se agregaron todas
        if (nuevasSolicitudes.length === solicitudesRaw.length) {
          this.solicitudes = nuevasSolicitudes;
        }
      });
    });

    // Si no hay solicitudes
    if (solicitudesRaw.length === 0) {
      this.solicitudes = [];
    }
  });
}
 amigosFiltrados() {//no buscaremos amigos, solo los mostraremos
  return this.amigos;
}
  enviarSolicitud(nickSolicitado: string) {
    const usuarioActual = this.sessionService.getUsuario();
    if (!usuarioActual) {
      alert('Debes iniciar sesión para enviar solicitudes.');
      return;
    }

    this.userService.enviarSolicitudAmistad({
      solicitante: usuarioActual,
      recibidor: nickSolicitado
    }).subscribe({
      next: () => {
        alert(`Solicitud enviada a ${nickSolicitado}`);
        this.busqueda = '';       // Limpia la búsqueda si quieres
        this.usuarios = [];       // Limpia resultados
        this.recargarSolicitudes(); // Recarga solicitudes para actualizar UI
      },
        error: (err) => {
        // 👇 acá accedemos al mensaje de error JSON enviado por el backend
        const mensaje = err?.error?.error || err.message || 'Error desconocido';
        alert(`Error al enviar solicitud: ${mensaje}`);
      }
    });
  }
}
