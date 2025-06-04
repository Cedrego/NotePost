import { Component } from '@angular/core';
import { BarraLateralComponent } from '../barra-lateral/barra-lateral.component'; //Borrar a Futuro es Testeo

@Component({
  selector: 'app-home',
  standalone: true,
  //imports: [],
  imports: [BarraLateralComponent], //Borrar a Futuro es Testeo
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss'
})
export class HomeComponent {

}
