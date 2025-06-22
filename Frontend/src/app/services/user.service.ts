import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  private baseUrl = 'http://localhost/NotePost/backend';

  constructor(private http: HttpClient) {}
/*
  getIdesAvatars(): Observable<{ avatares: number[] }> {
    return this.http.get<{ avatares: number[] }>(`${this.baseUrl}/procesarFormulario.php`);
  }
*/
  enviarCambiarAvatar(data: any): Observable<any> {
      return this.http.post(`${this.baseUrl}/implementacion/procesarFormulario.php`, data);
  }


  enviarCrearUsuario(data: any): Observable<any> {
      return this.http.post(`${this.baseUrl}/implementacion/procesarFormulario.php`, data);
  }

  enviarIniciarSesion(data: any): Observable<any> {
      return this.http.post(`${this.baseUrl}/implementacion/procesarFormulario.php`, data);
  }
  enviarPost(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/implementacion/procesarFormulario.php`, data);
  }

}
