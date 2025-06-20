import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class SessionService {

  setUsuario(nick: string): void {
    localStorage.setItem('usuario', nick);
  }

  getUsuario(): string | null {
    return localStorage.getItem('usuario');
  }

  isLoggedIn(): boolean {
    return this.getUsuario() !== null;
  }

  logout(): void {
    localStorage.removeItem('usuario');
  }
  
}
