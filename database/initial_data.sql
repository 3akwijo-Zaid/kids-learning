-- Insert categories
INSERT INTO categories (name) VALUES 
('Animals'),
('Alphabets'),
('Transport'),
('Fruits'),
('Colors'),
('Shapes');

-- Insert Animals
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(1, 'Lion', 'The lion is known as the king of the jungle. It has a big mane and is very strong.', 'uploads/animals/lion.jpg', 'uploads/animals/lion.mp3'),
(1, 'Elephant', 'Elephants are the largest land animals. They have long trunks and big ears.', 'uploads/animals/elephant.jpg', 'uploads/animals/elephant.mp3'),
(1, 'Giraffe', 'Giraffes have very long necks to reach leaves high in trees.', 'uploads/animals/giraffe.jpg', 'uploads/animals/giraffe.mp3'),
(1, 'Monkey', 'Monkeys are playful animals that love to swing from trees.', 'uploads/animals/monkey.jpg', 'uploads/animals/monkey.mp3'),
(1, 'Tiger', 'Tigers have orange fur with black stripes and are very fast runners.', 'uploads/animals/tiger.jpg', 'uploads/animals/tiger.mp3');

-- Insert Alphabets
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(2, 'A', 'A is for Apple. A juicy red fruit that grows on trees.', 'uploads/alphabets/a.jpg', 'uploads/alphabets/a.mp3'),
(2, 'B', 'B is for Ball. A round toy that bounces and rolls.', 'uploads/alphabets/b.jpg', 'uploads/alphabets/b.mp3'),
(2, 'C', 'C is for Cat. A furry pet that says meow.', 'uploads/alphabets/c.jpg', 'uploads/alphabets/c.mp3'),
(2, 'D', 'D is for Dog. A friendly pet that barks and wags its tail.', 'uploads/alphabets/d.jpg', 'uploads/alphabets/d.mp3'),
(2, 'E', 'E is for Elephant. A big animal with a long trunk.', 'uploads/alphabets/e.jpg', 'uploads/alphabets/e.mp3');

-- Insert Transport
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(3, 'Car', 'Cars have four wheels and take us places on roads.', 'uploads/transport/car.jpg', 'uploads/transport/car.mp3'),
(3, 'Bus', 'Buses are big vehicles that carry many people.', 'uploads/transport/bus.jpg', 'uploads/transport/bus.mp3'),
(3, 'Train', 'Trains run on tracks and have many connected cars.', 'uploads/transport/train.jpg', 'uploads/transport/train.mp3'),
(3, 'Airplane', 'Airplanes fly in the sky and have wings.', 'uploads/transport/airplane.jpg', 'uploads/transport/airplane.mp3'),
(3, 'Boat', 'Boats float on water and help us travel across rivers and seas.', 'uploads/transport/boat.jpg', 'uploads/transport/boat.mp3');

-- Insert Fruits
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(4, 'Apple', 'A sweet, crunchy fruit that is red, green, or yellow.', 'uploads/fruits/apple.jpg', 'uploads/fruits/apple.mp3'),
(4, 'Banana', 'A long, yellow fruit that is soft and sweet.', 'uploads/fruits/banana.jpg', 'uploads/fruits/banana.mp3'),
(4, 'Orange', 'A round, orange fruit that is juicy and full of vitamin C.', 'uploads/fruits/orange.jpg', 'uploads/fruits/orange.mp3'),
(4, 'Grapes', 'Small, round fruits that grow in bunches and can be green or purple.', 'uploads/fruits/grapes.jpg', 'uploads/fruits/grapes.mp3'),
(4, 'Strawberry', 'A small, red fruit with tiny seeds on the outside.', 'uploads/fruits/strawberry.jpg', 'uploads/fruits/strawberry.mp3');

-- Insert Colors
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(5, 'Red', 'Red is the color of apples and fire trucks.', 'uploads/colors/red.jpg', 'uploads/colors/red.mp3'),
(5, 'Blue', 'Blue is the color of the sky and the ocean.', 'uploads/colors/blue.jpg', 'uploads/colors/blue.mp3'),
(5, 'Yellow', 'Yellow is the color of the sun and bananas.', 'uploads/colors/yellow.jpg', 'uploads/colors/yellow.mp3'),
(5, 'Green', 'Green is the color of grass and leaves.', 'uploads/colors/green.jpg', 'uploads/colors/green.mp3'),
(5, 'Purple', 'Purple is the color of grapes and some flowers.', 'uploads/colors/purple.jpg', 'uploads/colors/purple.mp3');

-- Insert Shapes
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(6, 'Circle', 'A circle is round like a ball or a wheel.', 'uploads/shapes/circle.jpg', 'uploads/shapes/circle.mp3'),
(6, 'Square', 'A square has four equal sides and four corners.', 'uploads/shapes/square.jpg', 'uploads/shapes/square.mp3'),
(6, 'Triangle', 'A triangle has three sides and three corners.', 'uploads/shapes/triangle.jpg', 'uploads/shapes/triangle.mp3'),
(6, 'Rectangle', 'A rectangle has four sides and four corners, with opposite sides equal.', 'uploads/shapes/rectangle.jpg', 'uploads/shapes/rectangle.mp3'),
(6, 'Star', 'A star has five points and looks like the stars in the sky.', 'uploads/shapes/star.jpg', 'uploads/shapes/star.mp3'); 