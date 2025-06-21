import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Post } from '../models/post.model'; // Importa el modelo Post

@Injectable({
  providedIn: 'root'
})
export class PostService {
  private apiUrl = 'http://localhost/backend/implementacion/obtenerPosts.php'; // Cambia la URL según tu configuración

  constructor(private http: HttpClient) {}

  getPosts(): Observable<Post[]> {
    return this.http.get<Post[]>(this.apiUrl); // Usa el modelo Post para tipar la respuesta
  }
}