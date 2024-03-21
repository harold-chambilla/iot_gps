<template>
    <div id="map" style="height: 400px;"></div>
    <p>Latitud: {{ latitude }}</p>
    <p>Longitud: {{ longitude }}</p>
  </template>
  
  <script setup>
  // Dato a enviar a otro formulario se le pondra (*), los datos que no van a estar son estos (-), Datos que iran en infoWindows(+)
  // Datos que se van a quedar en la misma vista (/)
  import { ref, onMounted, onUnmounted, watch, computed } from 'vue';
  import axios from 'axios';
//   import edificioImg from '../../img/edificio-svg.png';
//   import hombreImg from '../../img/hombre-svg.png';
  
  // Variables de ubicación
  const latitude = ref(0);
  const longitude = ref(0);
  const exactitud = ref(0);
  const mapInitialized = ref(false);
  const watchId = ref(null);
  const verId = ref(null);
  const fileInput = ref(null);
  
  // Variables adicionales
  const distancia = ref(null);
  const asistencia = ref("");
  const exactitudRadio = ref(null);
  const estadoEntrada = ref("");
  
  const postCord = () => {
    try {
        const formData = new FormData();
        formData.append('latitud', latitude.value);
        formData.append('longitud', longitude.value);
        const response = axios.post('/api/ubicacion', formData);

        console.log('Respuesta del servidor:', response.data);
    } catch (error) {
        console.error('Error al enviar datos:', error);
    }
  }
  const watchCoordinates = () => {
    return new Promise((resolve, reject) => {
      verId.value = navigator.geolocation.watchPosition(
        (position) => {
          latitude.value = position.coords.latitude;
          longitude.value = position.coords.longitude;
          exactitud.value = position.coords.accuracy;

          postCord();
          
          resolve({ latitude: latitude.value, longitude: longitude.value, accuracy: exactitud.value });
        },
        (error) => {
          console.error('Geolocation error:', error);
          reject(error);
        },
        { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
      );
    });
  };
  
  onMounted(() => {
    if ('geolocation' in navigator) {
      setTimeout(() => {    
        const coordenadasInitMap = async () => {
          try {
            const coords = await watchCoordinates();
            // coords es un objeto con latitude y longitude
            latitude.value = coords.latitude;
            longitude.value = coords.longitude;
            exactitud.value = coords.accuracy;
            if (!mapInitialized.value) { // Función a aparecer al actualizar la web
              initMap(latitude.value, longitude.value, exactitud.value);
              // console.log("no creo: ", latitude.value);
            } 

            console.log('lat: ', latitude.value, 'and long: ', longitude.value);
          
                

          } catch (error) {
            console.error("Error al obtener coordenadas:", error);
          }
        };
        coordenadasInitMap();
      }, 500);
    }
  });
  
  // Esta función se ejecutará una vez que la API de Google Maps esté cargada y lista
  const initMap = (latitud, longitud, exact) => {
    if (!mapInitialized.value) {
      const ubiActual = { lat: latitud, lng: longitud };
      const ubiDestino = { lat: -12.085184, lng: -76.976101 }; // Ubicación de la empresa
  
      const mapOptions = {
        center: ubiActual,
        zoom: 18
      };
  
      const map = new google.maps.Map(document.getElementById('map'), mapOptions);
  
      const radio = 800;
      const circle = new google.maps.Circle({
        strokeColor: '#8CD0FF',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#8CD0FF',
        fillOpacity: 0.35,
        map: map,
        center: ubiDestino,
        radius: radio
      });
      const dist = google.maps.geometry.spherical.computeDistanceBetween(ubiActual, ubiDestino);
  
      const infoWindow = new google.maps.InfoWindow({
        content: '<p>Latitud: ' + latitud + '</p>' +
          '<p>Longitud: ' + longitud + '</p>' +
          '<p>Exactitud: ' + exact + '</p>' +
          '<p>Distancia: ' + dist + '</p>' +
          '<a href="https://www.google.com/maps/search/' + latitud + ',' + longitud + '">Ver en google maps</a>',
        map: map,
        arialabel: "Uluru",
        // pixelOffset: new google.maps.Size(0, -50)
      })
  
      const marker = [
        new google.maps.Marker({ position: ubiActual, map: map, title: "Aqui estoy!"}),
        // new google.maps.Marker({ position: ubiDestino, map: map, title: "Mi empresa" })
      ];
  
    //   const bounds = new google.maps.LatLngBounds();
    //   bounds.extend(ubiActual);
    //   bounds.extend(ubiDestino);
    //   // const padding = 50;
    //   // map.fitBounds(bounds, padding);
    //   map.fitBounds(bounds)
  
      //const lat = new google.maps.LatLng(-12.033077753513151, -76.99137861370299)
  
      const tiles = new google.maps.event.addListenerOnce(map, 'tilesloaded', () => {
        if (!mapInitialized.value) { // El mapa ya esta inicializado, por lo que no lo que el watch se limpiara y no se volvera a actualizar
          mapInitialized.value = true;
          // Limpiar el observador de ubicación una vez que el usuario esté satisfecho con la ubicación  
          navigator.geolocation.clearWatch(watchId.value);
        }
      });
  
      // infoWindow.open(map, marker[0]);
      marker[0].addListener("click", () => { //debido al emergente, el mapa se centra automaticamente en este, por eso tome como opcion cerrarlo para abrirlo.
        infoWindow.open({
          anchor: marker[0],
          map: map,
        });
      });
  
    }
  };

  </script>