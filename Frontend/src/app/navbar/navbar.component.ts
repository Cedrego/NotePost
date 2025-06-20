import { Component, inject } from '@angular/core';
import { Router } from '@angular/router';
import { MenuHamburguesaComponent } from '../menu-hamburguesa/menu-hamburguesa.component';
import { MenuUsuarioComponent } from "../menu-usuario/menu-usuario.component";
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [MenuHamburguesaComponent, MenuUsuarioComponent, CommonModule],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss'
})
export class NavbarComponent {
  constructor(private router: Router) {}
  sessionService = inject(SessionService);
  goToCreateUser() {
    this.router.navigate(['/create-user']);
  }

  goToHome() {
    this.router.navigate(['/home']);
  }
  goToIniciarSesion(){
    this.router.navigate(['/iniciar-sesion'])
  }
  
  get isLoggedIn(): boolean {
    return this.sessionService.isLoggedIn();
  }
  
}
