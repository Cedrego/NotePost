import { Component } from '@angular/core';
import { NgIf } from '@angular/common';

@Component({
  selector: 'app-menu-hamburguesa',
  standalone: true,
  imports: [NgIf],
  templateUrl: './menu-hamburguesa.component.html',
  styleUrl: './menu-hamburguesa.component.scss'
})
export class MenuHamburguesaComponent {
  menuAbierto = false;

  toggleMenu() {
    this.menuAbierto = !this.menuAbierto;
  }

  opcionSeleccionada(opcion: string) {
    console.log('Opción seleccionada:', opcion);
    this.menuAbierto = false;
  }
}
