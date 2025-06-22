import { Component, inject, OnInit } from '@angular/core';
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
export class CambiarAvatarComponent implements OnInit{
  
  avatarForm!: FormGroup;
  alertMessage = '';
  showAlert = false;

  constructor(private fb: FormBuilder, private userService: UserService) {}

  ngOnInit(): void {
    this.avatarForm = this.fb.group({
      idAvatar: ['', Validators.required]
    });
  }
    // Array de avatares
  avatars = [
    { id: 1, filename: 'B-Rojo.png', name: 'Avatar 1' },
    { id: 2, filename: 'W-Rojo.png', name: 'Avatar 2' },
    { id: 3, filename: 'B-Naranja.png', name: 'Avatar 3' },
    { id: 4, filename: 'W-Naranja.png', name: 'Avatar 4' },
    { id: 5, filename: 'B-Amarillo.png', name: 'Avatar 5' },
    { id: 6, filename: 'W-Amarillo.png', name: 'Avatar 6' },
    { id: 7, filename: 'B-Lima.png', name: 'Avatar 7' },
    { id: 8, filename: 'W-Lima.png', name: 'Avatar 8' },
    { id: 9, filename: 'B-Verde.png', name: 'Avatar 9' },
    { id: 10, filename: 'W-Verde.png', name: 'Avatar 10' },
    { id: 11, filename: 'B-Turquesa.png', name: 'Avatar 11' },
    { id: 12, filename: 'W-Turquesa.png', name: 'Avatar 12' },
    { id: 13, filename: 'B-Cyan.png', name: 'Avatar 13' },
    { id: 14, filename: 'W-Cyan.png', name: 'Avatar 14' },
    { id: 15, filename: 'B-Azul.png', name: 'Avatar 15' },
    { id: 16, filename: 'W-Azul.png', name: 'Avatar 16' },
    { id: 17, filename: 'B-Purpura.png', name: 'Avatar 17' },
    { id: 18, filename: 'W-Purpura.png', name: 'Avatar 18' },
    { id: 19, filename: 'B-Magenta.png', name: 'Avatar 19' },
    { id: 20, filename: 'W-Magenta.png', name: 'Avatar 20' },
    { id: 21, filename: 'B-Rosa.png', name: 'Avatar 21' },
    { id: 22, filename: 'W-Rosa.png', name: 'Avatar 22' },
    { id: 23, filename: 'B-Blanco.png', name: 'Avatar 23' },
    { id: 24, filename: 'W-Negro.png', name: 'Avatar 24' },
  ];

  onSubmit(): void {
    if (this.avatarForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.avatarForm.value;
    this.userService.enviarCambiarAvatar(formData).subscribe({
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

  get idAvatar() {
    return this.avatarForm.get('idAvatar');
  }
}

