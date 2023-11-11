def is_point_in_zone(point, zone):
    """
    Returns True if the given point is inside the given zone, False otherwise.
    """
    num_intersections = 0
    for i in range(len(zone)):
        p1 = zone[i]
        p2 = zone[(i + 1) % len(zone)]
        if point[1] > min(p1[1], p2[1]):
            if point[1] <= max(p1[1], p2[1]):
                if point[0] <= max(p1[0], p2[0]):
                    if p1[1] != p2[1]:
                        x_inters = (point[1] - p1[1]) * (p2[0] - p1[0]) / (p2[1] - p1[1]) + p1[0]
                        if p1[0] == p2[0] or point[0] <= x_inters:
                            num_intersections += 1
    return num_intersections % 2 == 1

point = (0.5, 0.5)

zone = [(0, 0), (1, 0), (1, 1), (0, 1)]

print(is_point_in_zone(point, zone))


# Definir una función para determinar si un punto está dentro de un polígono
# Recibe como parámetros las coordenadas x e y del punto y dos listas con las coordenadas x e y de los vértices del polígono
# Retorna True si el punto está dentro del polígono, False si está fuera
def punto_dentro_poligono(x, y, px, py):
  # Inicializar una variable para contar el número de cruces
  cruces = 0
  # Recorrer todos los vértices del polígono
  for i in range(len(px)):
    # Obtener el vértice actual y el siguiente (el último se conecta con el primero)
    x1 = px[i]
    y1 = py[i]
    x2 = px[(i + 1) % len(px)]
    y2 = py[(i + 1) % len(py)]
    # Verificar si el punto está sobre un vértice
    if x == x1 and y == y1:
      return True # El punto está dentro del polígono
    # Verificar si el punto está sobre un lado horizontal
    if y1 == y2 and y == y1 and x > min(x1, x2) and x < max(x1, x2):
      return True # El punto está dentro del polígono
    # Verificar si el punto está por debajo de ambos vértices
    if y < min(y1, y2):
      continue # No hay cruce
    # Verificar si el punto está por encima de ambos vértices
    if y > max(y1, y2):
      continue # No hay cruce
    # Verificar si el punto está a la izquierda de ambos vértices
    if x < min(x1, x2):
      cruces += 1 # Hay un cruce
    # Verificar si el punto está a la derecha de ambos vértices
    elif x > max(x1, x2):
      continue # No hay cruce
    # Verificar si el punto está sobre la línea que une los vértices
    else:
      # Calcular la pendiente y la intersección de la línea
      m = (y2 - y1) / (x2 - x1)
      b = y1 - m * x1
      # Comparar la coordenada y del punto con la de la línea
      if y == m * x + b:
        return True # El punto está dentro del polígono
      # Verificar si el punto está a la derecha de la línea
      elif y > m * x + b:
        cruces += 1 # Hay un cruce
  # Retornar el resultado según el número de cruces
  if cruces % 2 == 0:
    return False # El punto está fuera del polígono
  else:
    return True # El punto está dentro del polígono