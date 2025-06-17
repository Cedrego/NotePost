import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { MenuHamburguesaComponent } from '../menu-hamburguesa/menu-hamburguesa.component';
import { MenuUsuarioComponent } from "../menu-usuario/menu-usuario.component";
@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [MenuHamburguesaComponent, MenuUsuarioComponent],
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss'
})
export class NavbarComponent {
  constructor(private router: Router) {}

  goToCreateUser() {
    this.router.navigate(['/create-user']);
  }

  goToHome() {
    this.router.navigate(['/home']);
  }
  goToIniciarSesion(){
    this.router.navigate(['/iniciar-sesion'])
  }
  
}
