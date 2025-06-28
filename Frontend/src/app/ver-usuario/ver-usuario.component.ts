import { Component, inject } from '@angular/core';
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { ActivatedRoute } from '@angular/router'; // <-- agrega esto
import { UserService } from '../services/user.service';
import { CommonModule } from '@angular/common';
@Component({
  selector: 'app-ver-usuario',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './ver-usuario.component.html',
  styleUrl: './ver-usuario.component.scss'
})
export class VerUsuarioComponent {
  sessionService = inject(SessionService);
  route = inject(ActivatedRoute);
  userService = inject(UserService);

  nick: string | null = null;
  datosUsuario: any = null;

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
   editarPost(postId: number): void {
      // Implement your edit logic here
      console.log('Editar post con ID:', postId);
    }

    eliminarPost(postId: number): void {
      if (confirm('¿Estás seguro de que deseas eliminar este post?')) {
        this.userService.eliminarPost(postId).subscribe(() => {
          this.ngOnInit(); // Recarga los posts después de eliminar
        });
      }
    }
}
