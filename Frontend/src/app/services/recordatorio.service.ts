import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class RecordatorioService {
  private apiUrl = 'http://localhost/backend/implementacion/guardarRecordatorio.php';

  constructor(private http: HttpClient) {}

  guardarRecordatorio(usuario: string, tituloEvento: string, fechaRecordatorio: string): Observable<any> {
    const payload = { usuario, tituloEvento, fechaRecordatorio };
    return this.http.post<any>(this.apiUrl, payload);
  }
}