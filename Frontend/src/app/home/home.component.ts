import { Component, inject } from '@angular/core';
import { BarraLateralComponent } from '../barra-lateral/barra-lateral.component'; //Borrar a Futuro es Testeo
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { CommonModule } from '@angular/common';
@Component({
  selector: 'app-home',
  standalone: true,
  //imports: [],
  imports: [BarraLateralComponent, CommonModule], //Borrar a Futuro es Testeo
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss'
})
export class HomeComponent {
   sessionService = inject(SessionService);
  get isLoggedIn(): boolean {
    return this.sessionService.isLoggedIn();
  }
  
}
