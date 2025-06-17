import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
@Component({
  selector: 'app-menu-usuario',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './menu-usuario.component.html',
  styleUrl: './menu-usuario.component.scss'
})
export class MenuUsuarioComponent {
  nombre:string="pepe";
  menuAbierto = false;
  constructor(private router: Router) {}

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
