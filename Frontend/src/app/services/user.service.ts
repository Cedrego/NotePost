import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  private baseUrl = 'http://localhost/backend';

  constructor(private http: HttpClient) {}

  getIdesAvatars(): Observable<{ ide: string, image: string }[]> {
    return this.http.get<{ ide: string, image: string }[]>(`${this.baseUrl}/implementacion/crearPost.php`);
  }
  
enviarAvatar(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/implementacion/crearPost.php`, data);
  }


  enviarCrearUsuario(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/implementacion/crear_usuario.php`, data);
  }

  enviarIniciarSesion(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/implementacion/iniciar_sesion.php`, data);
  }
  enviarPost(data: any): Observable<any> {
    return this.http.post(`${this.baseUrl}/implementacion/procesarFormulario.php`, data);
  }

}
