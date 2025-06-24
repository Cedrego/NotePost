import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { NgIf } from '@angular/common';
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { UserService } from '../services/user.service';
@Component({
  selector: 'app-menu-usuario',
  standalone: true,
  imports: [CommonModule, NgIf],
  templateUrl: './menu-usuario.component.html',
  styleUrl: './menu-usuario.component.scss'
})
export class MenuUsuarioComponent {
  nombre:string | null;
  rutaAvatar: string = '';
  menuAbierto = false;
  constructor(private router: Router,
        public sessionService: SessionService
        , private userService: UserService
  ) {
    this.nombre = this.sessionService.getUsuario(); // <-- asigna el nick al crear el componente
  }
  ngOnInit(): void {
    this.nombre = this.sessionService.getUsuario();
    if (this.nombre) {
      this.userService.getRutaAvatar(this.nombre).subscribe(res => {
        this.rutaAvatar = res.ruta; // Aquí guardas la ruta recibida del backend
      });
    }
  }
  toggleMenu() {
    this.menuAbierto = !this.menuAbierto;
  }
  opcionSeleccionada(opcion: string) {
    console.log('Opción seleccionada:', opcion);
    this.menuAbierto = false;
  }
  goToCrearPost(): void {
    this.router.navigate(['/crear-post']);
  }

  goToCambiarAvatar(): void {
    this.router.navigate(['/cambiar-avatar']);
  }
  goToCambiarPrivacidad(): void {
    this.router.navigate(['/cambiar-privacidad']);
  }
}
