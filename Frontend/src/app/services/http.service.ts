import { HttpClient } from '@angular/common/http';
import { inject, Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class HttpService {

  http = inject(HttpClient)
  constructor() { }
  
  doGet(){
    return this.http.get('https://reqres.in/api/users?page=2')//A cambiar
  }
}
