import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

export interface UsuarioIde {
  ide: string;
  image: string;
}

@Injectable({
  providedIn: 'root'
})
export class UserService {
  private baseUrl = 'http://localhost/NotePost/backend';

  constructor(private http: HttpClient) {}
  
  getIdesAvatars(): Observable<{ ide: string, image: string }[]> {
    return this.http.get<{ ide: string, image: string }[]>(`${this.baseUrl}/archivo.php`);
  }
 /* getIdesAvatars(): Observable<UsuarioIde[]> {
    return this.http.get<UsuarioIde[]>(`${this.baseUrl}/archivo.php`);
  }
*/
  enviarPost(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/archivo.php`, data);
  }

  enviarCrearUsuario(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/archivo.php`, data);
  }

  enviarIniciarSesion(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/archivo.php`, data);
  }
}
