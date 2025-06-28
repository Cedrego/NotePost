import { Component, OnInit, inject, Input } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { PostService } from '../services/post.service';
import { Post } from '../models/post.model'; //importa el modelo Post
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';
import { SessionService } from '../services/session.service';

@Component({
  selector: 'app-filtered-post-list',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './filtered-post-list.component.html',
  styleUrls: ['./filtered-post-list.component.scss']
})

export class FilteredPostListComponent implements OnInit {
  tag: string = ''; //tag recibido desde la URL
  filteredPosts: Post[] = [];

  private userService = inject(UserService);
  sessionService = inject(SessionService);
  usuario = this.sessionService.getUsuario()
  constructor(private route: ActivatedRoute, private postService: PostService) {}

  ngOnInit(): void {
    // Obtener el tag desde los parámetros de la URL
    this.route.queryParams.subscribe((params) => {
      this.tag = params['tag'] || '';
      if (this.tag) {
        this.postService.getPostsByTag(this.tag).subscribe({
          next: (posts) => {
            this.filteredPosts = posts;
          },
          error: (error) => {
            console.error('Error al obtener posts filtrados:', error);
          }
        });
      }
    });
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