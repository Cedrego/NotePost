import { Component } from '@angular/core';
import { FullCalendarModule } from '@fullcalendar/angular';
import { CalendarOptions, DateSelectArg } from '@fullcalendar/core';
import { RecordatorioService } from '../services/recordatorio.service';
import esLocale from '@fullcalendar/core/locales/es';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

@Component({
  selector: 'app-calendario',
  standalone: true,
  imports: [FullCalendarModule],
  templateUrl: './calendario.component.html',
  styleUrls: ['./calendario.component.scss']
})
export class CalendarioComponent {
  constructor(private recordatorioService: RecordatorioService) {}

  calendarOptions: CalendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    selectable: true,
    editable: true,
    locale: esLocale,
    select: (info: DateSelectArg) => {
      const tituloEvento = prompt('Título del evento');
      if (tituloEvento) {
        const usuario = localStorage.getItem('usuario'); // Obtener el usuario logueado desde el almacenamiento local
        if (!usuario) {
          alert('Debes iniciar sesión para crear un recordatorio.');
          return;
        }

        this.recordatorioService.guardarRecordatorio(usuario, tituloEvento, info.start.toISOString()).subscribe(
          (response) => {
            alert('Post y recordatorio creados exitosamente');
            info.view.calendar.addEvent({
              title: tituloEvento,
              start: info.start,
              end: info.end,
              allDay: info.allDay
            });
          },
          (error) => {
            console.error('Error al crear el post y recordatorio:', error);
          }
        );
        info.view.calendar.unselect();
      }
    }
  };

  ngOnInit(): void {
    const usuario = localStorage.getItem('usuario'); // Obtener el usuario logueado
    if (!usuario) {
      alert('Debes iniciar sesión para ver tus recordatorios.');
      return;
    }
  
    this.recordatorioService.cargarRecordatoriosUsuario(usuario).subscribe(
      (recordatorios) => {
        //recrear los eventos en el calendario
        const eventos = recordatorios.map((recordatorio) => ({
          id: recordatorio.postId,
          title: recordatorio.contenido, 
          start: recordatorio.fechaRecordatorio, 
          end: recordatorio.fechaRecordatorio, 
          allDay: true
        }));
        this.calendarOptions.events = eventos; //asignar los eventos al calendario
      },
      (error) => {
        console.error('Error al obtener los recordatorios:', error);
      }
    );
  }
}