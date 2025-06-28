import { Component, inject, EventEmitter } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { MenuHamburguesaComponent } from '../menu-hamburguesa/menu-hamburguesa.component';
import { MenuUsuarioComponent } from "../menu-usuario/menu-usuario.component";
import { SessionService } from '../services/session.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [MenuHamburguesaComponent, MenuUsuarioComponent, CommonModule, FormsModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss'
})
export class NavbarComponent {
  constructor(private router: Router, private http: HttpClient) {}

  tagSelected = new EventEmitter<string>();
  sessionService = inject(SessionService);
  searchTag: string = '';

  goToCreateUser() {
    this.router.navigate(['/create-user']);
  }

  goToHome() {
    this.router.navigate(['/home']);
  }

  goToIniciarSesion() {
    this.router.navigate(['/iniciar-sesion']);
  }

  get isLoggedIn(): boolean {
    return this.sessionService.isLoggedIn();
  }

  searchPostsByTag() {
    if (!this.searchTag.trim()) {
      alert('Por favor, ingresa un tag para buscar.');
      return;
    }

    this.router.navigate(['/filtered-posts'], {
      queryParams: { tag: this.searchTag }
    });
    this.searchTag = ''; // Limpiar el campo de búsqueda después de la búsqueda
  }
}