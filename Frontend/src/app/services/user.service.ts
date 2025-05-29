import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class UserService {
  /*private baseUrl = 'https://reqres.in/api/users';

  constructor(private http: HttpClient) {}

  createUser(data: any): Observable<any> {
    return this.http.post(this.baseUrl, data);
  }

  getUsers(): Observable<any> {
    return this.http.get(`${this.baseUrl}?page=1`);
  }*/
}
