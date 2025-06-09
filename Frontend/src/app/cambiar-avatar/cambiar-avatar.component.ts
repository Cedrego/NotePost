import { Component, inject } from '@angular/core';
import {
  FormBuilder, FormGroup, Validators,
  ReactiveFormsModule, FormsModule
} from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-cambiar-avatar',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './cambiar-avatar.component.html',
  styleUrl: './cambiar-avatar.component.scss'
})
export class CambiarAvatarComponent {

  avatarForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);

  showAlert = false;
  alertMessage = '';
  id = 10;

  constructor() {
    this.avatarForm = this.fb.group({
      idAvatar: ['1'] 
    });
  }

  onSubmit(): void {
    if (this.avatarForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.avatarForm.value;

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
  get idAvatar() { return this.avatarForm.get('idAvatar'); }
}
