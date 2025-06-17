import { Component, inject } from '@angular/core';
import {
  FormBuilder, FormGroup, Validators,
  ReactiveFormsModule, FormsModule
} from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-cambiar-privacidad',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './cambiar-privacidad.component.html',
  styleUrl: './cambiar-privacidad.component.scss'
})
export class CambiarPrivacidadComponent {
  privacidadForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);

  showAlert = false;
  alertMessage = '';
  id = 10;

  constructor() {
    this.privacidadForm = this.fb.group({
    //  contenido: ['', Validators.required],
      Pricvado: [],
    });
  }

  onSubmit(): void {
    if (this.privacidadForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.privacidadForm.value;
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
  get Pricvado() { return this.privacidadForm.get('Pricvado'); }
  
}
