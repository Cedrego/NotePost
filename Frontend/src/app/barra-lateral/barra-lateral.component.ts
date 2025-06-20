import { Component, OnInit, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; 
import { CalendarioComponent } from '../calendario/calendario.component';
import { UserService } from '../services/user.service';
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión

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
  amigos: string[] = []; 
  solicitudes: { solicitante: string, recibidor: string, aceptada: number }[] = [];

  ides: { ide: string, image: string }[] = []; // Para mostrar los IDEs con imagen

  ngOnInit(): void {
    this.recargarSolicitudes();
    // Cargar amigos y solicitudes de amistad al iniciar el componente
      this.userService.getSolicitudesDeAmistad().subscribe(res => {
      this.solicitudes = res.Solicitudes ?? [];
      console.log('Solicitudes:', this.solicitudes);
    });
     this.userService.getAmigos().subscribe(res => {
      this.amigos = res.amigos ?? [];
    });
    this.userService.getIdesAvatars().subscribe(data => {
      this.ides = data;
    });
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
      this.amigos = res.amigos ?? [];
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
      this.solicitudes = res.Solicitudes ?? [];
    });
  }
  amigosFiltrados() {
    return this.amigos.filter(a =>
      a.toLowerCase().includes(this.busqueda.toLowerCase())
    );
  }
}
