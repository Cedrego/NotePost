import { Component, inject } from '@angular/core';
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { ActivatedRoute } from '@angular/router'; // <-- agrega esto
import { UserService } from '../services/user.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
@Component({
  selector: 'app-ver-usuario',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './ver-usuario.component.html',
  styleUrl: './ver-usuario.component.scss'
})
export class VerUsuarioComponent {
  sessionService = inject(SessionService);
  route = inject(ActivatedRoute);
  userService = inject(UserService);

  nick: string | null = null;
  datosUsuario: any = null;

  postEnEdicion: number | null = null;
  contenidoEditado: string = '';
  fechaEditada: string = ''; // formato yyyy-MM-ddTHH:mm para datetime-local
  estadoEditado: boolean = false;
  
  ngOnInit(): void {
    this.route.queryParamMap.subscribe(params => {
      this.nick = params.get('nick');
      if (this.nick) {
        const nickSesion = this.sessionService.getUsuario();
        const esPropietario = (nickSesion === this.nick);
        this.userService.getDatosUsuario(this.nick, esPropietario).subscribe(datos => {
          this.datosUsuario = datos;
        });
      }
    });
  }
    darLike(postId: number) {
    const usuario = this.sessionService.getUsuario();
     if (usuario) {
      this.userService.darLike(postId, usuario).subscribe(() => {
        this.ngOnInit();
      });
    } else {
      console.error("No hay usuario logueado para dar like");
    }
  }

  darDislike(postId: number) {
    const usuario = this.sessionService.getUsuario();
      if (usuario) {
        this.userService.darDislike(postId, usuario).subscribe(() => {
          this.ngOnInit();
        });
      } else {
        console.error("No hay usuario logueado para dar like");
      }
    }
   editarPost(post: any) {
    this.postEnEdicion = post.id;
    this.contenidoEditado = post.contenido;
    // Convertimos la fecha del post al formato de input type="datetime-local"
    if (post.fechaRecordatorio) {
      const fecha = new Date(post.fechaRecordatorio);
      const local = new Date(fecha.getTime() - fecha.getTimezoneOffset() * 60000);
      this.fechaEditada = local.toISOString().slice(0, 16); // yyyy-MM-ddTHH:mm
    } else {
      this.fechaEditada = '';
    }
    this.estadoEditado = post.privado;
  }

  cancelarEdicion() {
    this.postEnEdicion = null;
    this.contenidoEditado = '';
    this.fechaEditada = '';
  }

  guardarEdicion(postId: number) {
    if (!this.contenidoEditado.trim() || !this.fechaEditada) {
      alert('El contenido y la fecha son obligatorios.');
      return;
    }

    this.userService.editarPost(postId, {
      contenido: this.contenidoEditado,
      fecha: this.fechaEditada,
      privado: this.estadoEditado
    }).subscribe({
      next: () => {
       const post = this.datosUsuario.posts.find((p: any) => p.id === postId);
        if (post) {
          post.contenido = this.contenidoEditado;
          post.fechaPost = this.fechaEditada;
        }

        this.cancelarEdicion();
      },
     error: err => {
          console.error('Error completo:', err); // 👈
          alert('Error al guardar: ' + (err?.error?.message || 'desconocido'));
        }
    });
  }

    eliminarPost(postId: number): void {
      if (confirm('¿Estás seguro de que deseas eliminar este post?')) {
        this.userService.eliminarPost(postId).subscribe(() => {
          this.ngOnInit(); // Recarga los posts después de eliminar
        });
      }
    }
}
