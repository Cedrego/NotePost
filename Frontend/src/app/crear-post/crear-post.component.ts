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

  coloresFondo = [
    { id: 1, name: 'Rojo', color: '#FFBFC0' },
    { id: 2, name: 'Naranja', color: '#FFD5BF' },
    { id: 3, name: 'Amarillo', color: '#FFFABF' },
    { id: 4, name: 'Lima', color: '#EBFFBF' },
    { id: 5, name: 'Verde', color: '#E0FFBF' },
    { id: 6, name: 'Turquesa', color: '#BFFFDB' },
    { id: 7, name: 'Cyan', color: '#BFFFFD' },
    { id: 8, name: 'Azul', color: '#BFD1FF' },
    { id: 9, name: 'Purpura', color: '#D0BFFF' },
    { id: 10, name: 'Magenta', color: '#FFBFD7' },
    { id: 11, name: 'Rosa', color: '#FCBFFF' },
  ];

  constructor() {
    this.postForm = this.fb.group({
      contenido: ['', Validators.required],
      privado: [true],
      tags: ['', Validators.required],
      usarRecordatorio: [false], // <-- nuevo control
      recordatorio: [''],
      fondo: ['1'] 
    });
  }

  onSubmit(): void {
    if (this.postForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }
    let formValue = this.postForm.value;
    // Si no quiere recordatorio, envía el campo vacío
      if (!formValue.usarRecordatorio) {
        formValue.recordatorio = '';
      }
    const formData = {
     ...formValue,
    usuario: this.sessionService.getUsuario() // o como obtengas el usuario logueado
    };
    // Eliminar usarRecordatorio no lo necesitas en el backend
    delete formData.usarRecordatorio;
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
  get recordatorio() { return this.postForm.get('recordatorio'); }
  get fondo() { return this.postForm.get('fondo'); }
}
