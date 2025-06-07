import { Component } from '@angular/core';
import { FullCalendarModule } from '@fullcalendar/angular';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import { CalendarOptions, DateSelectArg } from '@fullcalendar/core';
import esLocale from '@fullcalendar/core/locales/es';

@Component({
  selector: 'app-calendario',
  standalone: true,
  imports: [FullCalendarModule],
  templateUrl: './calendario.component.html',
  styleUrls: ['./calendario.component.scss']
})
export class CalendarioComponent {
  calendarOptions: CalendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    selectable: true,
    editable: true,
    locale: esLocale,
    select: (info: DateSelectArg) => {
      const title = prompt('Título del evento');
      if (title) {
        info.view.calendar.addEvent({
          title,
          start: info.start,
          end: info.end,
          allDay: info.allDay
        });
      }
      info.view.calendar.unselect();
    }
  };
}
