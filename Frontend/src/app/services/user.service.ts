import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  private baseUrl = 'http://localhost/NotePost/backend';

  constructor(private http: HttpClient) {}
  
  getIdesAvatars(): Observable<{ ide: string, image: string }[]> {
    return this.http.get<{ ide: string, image: string }[]>(`${this.baseUrl}/archivo.php`);
  }
  enviarAvatar(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/cambiarAvatar.php`, data);
  }

  enviarPost(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/crearPost.php`, data);
  }

  enviarCrearUsuario(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/archivo.php`, data);
  }

  enviarIniciarSesion(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/archivo.php`, data);
  }
}
