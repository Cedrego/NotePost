import { Component, OnInit, inject } from '@angular/core';
import { PostService } from '../services/post.service';
import { Post } from '../models/post.model';
import { CommonModule } from '@angular/common';
import { SessionService } from '../services/session.service';
import { UserService } from '../services/user.service';
@Component({
  selector: 'app-ranking',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './ranking.component.html',
  styleUrls: ['./ranking.component.scss']
})
export class RankingComponent implements OnInit {
  posts: Post[] = [];
  sessionService = inject(SessionService);
  isLoggedIn = this.sessionService.isLoggedIn();
  private userService = inject(UserService);
  constructor(private postService: PostService) {}

  ngOnInit(): void {
    this.postService.getRanking().subscribe(
      (data: Post[]) => {
        this.posts = data;
      },
      (error: any) => {
        console.error('Error al obtener el ranking:', error);
      }
    );
  }
   darLike(postId: number) {
    const usuario = this.sessionService.getUsuario();
     if (usuario) {
      this.userService.darLike(postId, usuario).subscribe(() => {
        this.ngOnInit();
      });
    } else {
      console.error("No hay usuario logueado para dar like");
    }
  }

  darDislike(postId: number) {
    const usuario = this.sessionService.getUsuario();
      if (usuario) {
        this.userService.darDislike(postId, usuario).subscribe(() => {
          this.ngOnInit();
        });
      } else {
        console.error("No hay usuario logueado para dar like");
      }
    }
}