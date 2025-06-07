import { Component, OnInit, inject } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule } from '@angular/common'; 
import { CalendarioComponent } from '../calendario/calendario.component';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-barra-lateral',
  standalone: true,
  imports: [FormsModule, CommonModule, CalendarioComponent],
  templateUrl: './barra-lateral.component.html',
  styleUrl: './barra-lateral.component.scss'
})
export class BarraLateralComponent implements OnInit {
  private userService = inject(UserService); // ✅ usando inject()
  
  busqueda = '';
  amigos = ['Amigo 1', 'Amigo 2', 'Paulo', 'Amigo 3', 'Adolfo'];

  ides: { ide: string, image: string }[] = []; // Para mostrar los IDEs con imagen

  ngOnInit(): void {
    this.userService.getIdesAvatars().subscribe(data => {
      this.ides = data;
    });
  }

  amigosFiltrados() {
    return this.amigos.filter(a =>
      a.toLowerCase().includes(this.busqueda.toLowerCase())
    );
  }
}
