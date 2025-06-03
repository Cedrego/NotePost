import { Component } from '@angular/core';
import { Router } from '@angular/router';
@Component({
  selector: 'app-navbar',
  imports: [],
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
  goToCrearPost(){
    this.router.navigate(['/crear-post'])
  }

}
