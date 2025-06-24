import { Component, OnInit } from '@angular/core';
import { PostService } from '../services/post.service';
import { Post } from '../models/post.model'; //importa el modelo Post
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-post-list',
  standalone: true,
  imports: [CommonModule], // <-- Agrega esto
  templateUrl: './post-list.component.html',
  styleUrls: ['./post-list.component.scss']
})
export class PostListComponent implements OnInit {
  posts: Post[] = []; //usa el modelo Post para tipar los posts

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
}
