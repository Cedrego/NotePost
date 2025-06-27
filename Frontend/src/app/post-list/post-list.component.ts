import { Component, OnInit, inject } from '@angular/core';
import { PostService } from '../services/post.service';
import { Post } from '../models/post.model'; //importa el modelo Post
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';
import { SessionService } from '../services/session.service';
@Component({
  selector: 'app-post-list',
  standalone: true,
  imports: [CommonModule], // <-- Agrega esto
  templateUrl: './post-list.component.html',
  styleUrls: ['./post-list.component.scss']
})
export class PostListComponent implements OnInit {
  posts: Post[] = []; //usa el modelo Post para tipar los posts
  private userService = inject(UserService);
  sessionService = inject(SessionService);
  usuario = this.sessionService.getUsuario()
  constructor(private postService: PostService) {}

  ngOnInit(): void {
    this.postService.getPosts().subscribe(
      (data: Post[]) => {
        this.posts = data;
      },
      (error: any) => {
        console.error('Error al obtener los posts:', error);
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