import { Component, OnInit, inject } from '@angular/core';
import { PostService } from '../services/post.service';
import { Post } from '../models/post.model';
import { CommonModule } from '@angular/common';
import { SessionService } from '../services/session.service';

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
}