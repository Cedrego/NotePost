import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { SessionService } from './session.service'; // importa el servicio
@Injectable({
  providedIn: 'root'
})
export class UserService {
  private baseUrl = 'http://localhost/backend';

  constructor(private http: HttpClient, private session: SessionService) {}

  getIdesAvatars(): Observable<{ ide: string, image: string }[]> {
    return this.http.get<{ ide: string, image: string }[]>(`${this.baseUrl}/implementacion/crearPost.php`);
  }
    
  getAmigos(): Observable<any> {
   const nick = this.session.getUsuario();
    return this.http.get(`${this.baseUrl}/implementacion/obtenerAmigos.php?usuario=${encodeURIComponent(nick ?? '')}`);
  }
  getSolicitudesDeAmistad(): Observable<any> {
   const nick = this.session.getUsuario();
    return this.http.get(`${this.baseUrl}/implementacion/obtenerSolicitudesPendites.php?usuario=${encodeURIComponent(nick ?? '')}`);
  }
  aceptarSolicitud(solicitud: { solicitante: string, recibidor: string }): Observable<any> {
    return this.http.post(`${this.baseUrl}/implementacion/aceptarSolicitud.php`, solicitud);
  }

  getRutaAvatar(nick: string): Observable<{ ruta: string }> {
    return this.http.get<{ ruta: string }>(
      `${this.baseUrl}/implementacion/obtenerRutaAvatar.php?usuario=${encodeURIComponent(nick)}`
    );
  }
  rechazarSolicitud(solicitud: { solicitante: string, recibidor: string }): Observable<any> {
    return this.http.post(`${this.baseUrl}/implementacion/rechazarSolicitud.php`, solicitud);
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
