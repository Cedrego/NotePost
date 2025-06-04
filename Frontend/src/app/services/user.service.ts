import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  constructor(private http: HttpClient) {}

  enviarPost(data: any): Observable<any> {
    return this.http.post('http://localhost/mi-servidor/archivo.php', data);
  }
  enviarCrearUsuario(data: any): Observable<any> {
    return this.http.post('http://localhost/mi-servidor/archivo.php', data);
  }
  enviarIniciarSesion(data: any): Observable<any> {
    return this.http.post('http://localhost/mi-servidor/archivo.php', data);
  }
}
