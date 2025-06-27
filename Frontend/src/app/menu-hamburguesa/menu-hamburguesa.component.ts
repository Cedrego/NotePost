import { Component, ElementRef, HostListener } from '@angular/core';
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

  constructor(
    private elRef: ElementRef,
    private router: Router // <-- Inyectar Router acá
  ) {}

  toggleMenu() {
    this.menuAbierto = !this.menuAbierto;
  }

  goToHome() {
    this.router.navigate(['/home']);
    this.menuAbierto = false;
  }

  opcionSeleccionada(opcion: string) {
    console.log('Opción seleccionada:', opcion);
    this.menuAbierto = false;
  }

  // Cierra el menú si el clic ocurre fuera del componente
  @HostListener('document:click', ['$event.target'])
  onClickFuera(target: HTMLElement) {
    const clicDentro = this.elRef.nativeElement.contains(target);
    if (!clicDentro && this.menuAbierto) {
      this.menuAbierto = false;
    }
  }
}