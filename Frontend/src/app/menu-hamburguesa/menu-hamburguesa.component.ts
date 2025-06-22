import { Component, HostListener } from '@angular/core';
import { NgIf } from '@angular/common';
import { Router } from '@angular/router';

@Component({
  selector: 'app-menu-hamburguesa',
  standalone: true,
  imports: [NgIf],
  templateUrl: './menu-hamburguesa.component.html',
  styleUrl: './menu-hamburguesa.component.scss'
})
export class MenuHamburguesaComponent {
  menuAbierto = false;

  constructor(private router: Router) {}

  toggleMenu() {
    this.menuAbierto = !this.menuAbierto;
  }

  goToHome(): void {
    this.router.navigate(['/home']);
    this.menuAbierto = false;
  }

  opcionSeleccionada(opcion: string) {
    console.log('Opción seleccionada:', opcion);
    this.menuAbierto = false;
  }

  @HostListener('document:click', ['$event'])
  clickFuera(event: MouseEvent) {
    const targetElement = event.target as HTMLElement;
    const clickedInside = targetElement.closest('.menu-hamburguesa');

    if (!clickedInside && this.menuAbierto) {
      this.menuAbierto = false;
    }
  }
}