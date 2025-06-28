import { Component, ElementRef, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { NgIf } from '@angular/common';
import { SessionService } from '../services/session.service';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-menu-usuario',
  standalone: true,
  imports: [CommonModule, NgIf],
  templateUrl: './menu-usuario.component.html',
  styleUrl: './menu-usuario.component.scss'
})
export class MenuUsuarioComponent {
  nombre: string | null;
  rutaAvatar: string = '';
  menuAbierto = false;

  constructor(
    private router: Router,
    public sessionService: SessionService,
    private userService: UserService,
    private elRef: ElementRef  // <-- referencia al elemento del componente
  ) {
    this.nombre = this.sessionService.getUsuario();
  }

  ngOnInit(): void {
    this.nombre = this.sessionService.getUsuario();
    if (this.nombre) {
      this.userService.getRutaAvatar(this.nombre).subscribe(res => {
        this.rutaAvatar = res.ruta;
      });
    }
  }

  toggleMenu() {
    this.menuAbierto = !this.menuAbierto;
  }

  opcionSeleccionada(opcion: string) {
    console.log('Opción seleccionada:', opcion);
    this.menuAbierto = false;
  }

  cerrarSesion(): void {
    localStorage.removeItem('usuario'); // o la clave que uses
    this.router.navigate(['/home']);
    this.menuAbierto = false;
  }


  goToCrearPost(): void {
    this.router.navigate(['/crear-post']);
    this.menuAbierto = false;
  }

  goToCambiarAvatar(): void {
    this.router.navigate(['/cambiar-avatar']);
    this.menuAbierto = false;
  }

  goToVerPerfil(): void {
    this.router.navigate(['/ver-usuario']);
    this.menuAbierto = false;
  }

  @HostListener('document:click', ['$event.target'])
  public onClickFuera(targetElement: any): void {
    const clickedInside = this.elRef.nativeElement.contains(targetElement);
    if (!clickedInside && this.menuAbierto) {
      this.menuAbierto = false;
    }
  }
}
