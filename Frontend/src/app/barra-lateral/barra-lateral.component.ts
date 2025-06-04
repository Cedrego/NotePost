import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; 
import { CalendarioComponent } from '../calendario/calendario.component';



@Component({
  selector: 'app-barra-lateral',
  standalone: true,
  imports: [FormsModule, CommonModule, CalendarioComponent],
  templateUrl: './barra-lateral.component.html',
  styleUrl: './barra-lateral.component.scss'
})
export class BarraLateralComponent {
  busqueda = '';
    amigos = ['Amigo 1', 'Amigo 2', 'Paulo', 'Amigo 3', 'Adolfo'];// Lista de amigos(prueba)

    amigosFiltrados() {
      return this.amigos.filter(a =>
        a.toLowerCase().includes(this.busqueda.toLowerCase())
      );
    }
}