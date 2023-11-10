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