import { Component, inject } from '@angular/core';
import {
  FormBuilder, FormGroup, Validators,
  ReactiveFormsModule, FormsModule
} from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';
import { SessionService } from '../services/session.service';
@Component({
  selector: 'app-crear-post',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './crear-post.component.html',
  styleUrl: './crear-post.component.scss'
})
export class CrearPostComponent {
  postForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);
  sessionService = inject(SessionService);
  showAlert = false;
  alertMessage = '';
  id = 10;

  ColorFondo: { id: number, rutaImagen: string }[] = [];

  constructor() {
    this.postForm = this.fb.group({
      contenido: ['', Validators.required],
      privado: [true],
      tags: ['', Validators.required],
      recordatorio: ['', Validators.required],
      fondo: ['1'] 
    });
  }
  ngOnInit(): void {
    this.userService.getTodosFondos().subscribe({
      next: (data: any) => {
        // Si tu backend devuelve { Solicitudes: [...] }
        this.ColorFondo = data.Solicitudes ?? [];
      },
      error: (error: any) => {
        this.alertMessage = 'Error al cargar avatares';
        this.showAlert = true;
      }
    });
  }

  onSubmit(): void {
    if (this.postForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }
    let formValue = this.postForm.value;

    const formData = {
     ...formValue,
    usuario: this.sessionService.getUsuario() // o como obtengas el usuario logueado
  };
    console.log('FormData enviado:', formData);

    this.userService.enviarPost(formData).subscribe({
    next: (respuesta:any) => {
      this.alertMessage = '¡Enviado correctamente!';
      this.showAlert = true;
      console.log('Respuesta del servidor:', respuesta);
    },
    error: (error: any) => {
      this.alertMessage = 'Error al enviar';
      this.showAlert = true;
      console.error('Error completo:', error);
      if (error.status === 400) {
        console.error('Error 400 - Bad Request. Probablemente JSON malformado o encabezado incorrecto.');
      }
    }
  });
  }

  // Getters para los campos
  get contenido() { return this.postForm.get('contenido'); }
  get privado() { return this.postForm.get('privado'); }
  get tags() { return this.postForm.get('tags'); }
  get recordatorio() { return this.postForm.get('recordatorio'); }
  get fondo() { return this.postForm.get('fondo'); }
}
