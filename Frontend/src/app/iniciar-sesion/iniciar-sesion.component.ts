import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule, FormsModule, AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-iniciar-sesion',
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './iniciar-sesion.component.html',
  styleUrl: './iniciar-sesion.component.scss'
})
export class IniciarSesionComponent implements OnInit{
  sesionForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);

  showAlert = false;
  alertMessage = '';
  id = 10;

  constructor() {
    this.sesionForm = this.fb.group({
      nick: ['', [Validators.required]],
      pass: ['', [Validators.required]],
      repeatpass: ['', Validators.required]
     });
  }

  ngOnInit(): void {
  }


  onSubmit(): void {
    if (this.sesionForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.sesionForm.value;

    this.userService.enviarIniciarSesion(formData).subscribe({
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
  get nick() { return this.sesionForm.get('email'); }
  get pass() { return this.sesionForm.get('pass'); }
  get repeatpass() { return this.sesionForm.get('repeatpass'); }


}
