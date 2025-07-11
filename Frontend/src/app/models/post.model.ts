export interface Post {
    id: number; // ID del post
    autor: string; // Nickname del autor
    contenido: string; // Contenido del post
    likes: number; // Número de likes
    dislikes: number; // Número de dislikes
    fechaPost: string; // Fecha de publicación (en formato ISO o string)
    privado: boolean; // Indica si el post es privado
    fondoRuta: string; // ← nueva propiedad para la URL del fondo
    tags: string[]; // Si usás nombres de tags
  }