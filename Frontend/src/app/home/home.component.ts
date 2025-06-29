import { Component, inject } from '@angular/core';
import { BarraLateralComponent } from '../barra-lateral/barra-lateral.component'; //Borrar a Futuro es Testeo
import { PostListComponent } from '../post-list/post-list.component';
import { SessionService } from '../services/session.service';// <-- importando servicio de sesión
import { RankingComponent } from '../ranking/ranking.component';
import { CommonModule } from '@angular/common';
@Component({
  selector: 'app-home',
  standalone: true,
  //imports: [],
  imports: [BarraLateralComponent, CommonModule, PostListComponent, RankingComponent], //Borrar a Futuro es Testeo
  templateUrl: './home.component.html',
  styleUrl: './home.component.scss'
})
export class HomeComponent {
    sessionService = inject(SessionService);
    get isLoggedIn(): boolean {
    return this.sessionService.isLoggedIn();
  }
  
}
