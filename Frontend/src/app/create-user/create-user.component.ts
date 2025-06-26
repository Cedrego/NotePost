import { Component, inject, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule, FormsModule, AbstractControl, ValidationErrors, ValidatorFn } from '@angular/forms';
import { CommonModule } from '@angular/common';
import { UserService } from '../services/user.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-create-user',
  standalone: true,
  imports: [CommonModule, FormsModule, ReactiveFormsModule],
  templateUrl: './create-user.component.html',
  styleUrls: ['./create-user.component.scss']
})
export class CreateUserComponent implements OnInit {

  userForm: FormGroup;
  fb = inject(FormBuilder);
  userService = inject(UserService);

  showAlert = false;
  alertMessage = '';
  id = 10;

  constructor(private router: Router,) {
    this.userForm = this.fb.group({
      nick: ['', Validators.required],
      nombre: ['', Validators.required],
      apellido: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      pass: ['', [Validators.required]],
      repeatpass: ['', Validators.required]
     });
  }

  ngOnInit(): void {
  }

  

  onSubmit(): void {
    if (this.userForm.invalid) {
      this.alertMessage = 'Formulario inválido';
      this.showAlert = true;
      return;
    }

    const formData = this.userForm.value;

    this.userService.enviarCrearUsuario(formData).subscribe({
      next: (respuesta:any) => {
        this.alertMessage = '¡Enviado correctamente!';
        this.showAlert = true;
        console.log('Respuesta del servidor:', respuesta);
        setTimeout(() => {
          this.router.navigate(['/iniciar-sesion']);
        }, 1000);

      },
      error: (error: any) => {
        if (error.status === 409 && error.error && error.error.error) {
          this.alertMessage = error.error.error; // Mensaje del backend: "El nick ya existe"
        } else {
          this.alertMessage = 'Error al enviar';
        }
        this.showAlert = true;
        console.error('Error:', error);
      }
    });
  }
  // Getters para los campos
  get nick() { return this.userForm.get('nickName'); }
  get nombre() { return this.userForm.get('nombre'); }
  get apellido() { return this.userForm.get('apellido'); }
  get email() { return this.userForm.get('email'); }
  get pass() { return this.userForm.get('pass'); }
  get repeatpass() { return this.userForm.get('repeatpass'); }

}

