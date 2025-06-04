import { Component, inject } from '@angular/core';
import {
  FormBuilder, FormGroup, Validators,
  ReactiveFormsModule, FormsModule
} from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';

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

  showAlert = false;
  alertMessage = '';
  id = 10;

  constructor() {
    this.postForm = this.fb.group({
      contenido: ['', Validators.required],
      privado: [''],
      recordatorio: [''],
      fondo: ['fondo1'] 
    });
  }

  onSubmit(): void {
    if (this.postForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.postForm.value;

    this.userService.enviarPost(formData).subscribe({
      next: (respuesta) => {
        this.alertMessage = '¡Enviado correctamente!';
        this.showAlert = true;
        console.log('Respuesta del servidor:', respuesta);
      },
      error: (error) => {
        this.alertMessage = 'Error al enviar';
        this.showAlert = true;
        console.error('Error:', error);
      }
    });
  }

  // Getters para los campos
  get contenido() { return this.postForm.get('contenido'); }
  get privado() { return this.postForm.get('privado'); }
  get recordatorio() { return this.postForm.get('recordatorio'); }
  get fondo() { return this.postForm.get('fondo'); }
}
