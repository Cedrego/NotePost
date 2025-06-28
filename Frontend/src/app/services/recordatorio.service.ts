import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class RecordatorioService {
  private guardarRecordatorioUrl = 'http://localhost/backend/implementacion/guardarRecordatorio.php';
  private obtenerRecordatoriosUrl = 'http://localhost/backend/implementacion/obtenerRecodatoriosPendientes.php';
  private cargarRecordatoriosUsuarioUrl = 'http://localhost/backend/implementacion/cargarRecordatoriosUsuario.php';
  constructor(private http: HttpClient) {}

  guardarRecordatorio(usuario: string, tituloEvento: string, fechaRecordatorio: string): Observable<any> {
    const payload = { usuario, tituloEvento, fechaRecordatorio };
    return this.http.post<any>(this.guardarRecordatorioUrl, payload);
  }

  obtenerRecordatoriosPorUsuario(usuario: string): Observable<any[]> {
    const payload = { usuario };
    return this.http.post<any[]>(this.obtenerRecordatoriosUrl, payload);
  }

  cargarRecordatoriosUsuario(usuario: string): Observable<any[]> {
    const url = `${this.cargarRecordatoriosUsuarioUrl}?usuario=${encodeURIComponent(usuario)}`;
    return this.http.get<any[]>(url);
  }
}