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
(1, 'Zebra', 'Zebras have black and white stripes and are very fast.', 'uploads/animals/zebra.jpg', 'uploads/animals/zebra.mp3'),
(1, 'Panda', 'Pandas are black and white bears that love to eat bamboo.', 'uploads/animals/panda.jpg', 'uploads/animals/panda.mp3'),
(1, 'Kangaroo', 'Kangaroos have strong legs and can jump very high.', 'uploads/animals/kangaroo.jpg', 'uploads/animals/kangaroo.mp3'),
(1, 'Dolphin', 'Dolphins are smart and friendly animals that live in the ocean.', 'uploads/animals/dolphin.jpg', 'uploads/animals/dolphin.mp3'),
(1, 'Penguin', 'Penguins are birds that cannot fly but are great swimmers.', 'uploads/animals/penguin.jpg', 'uploads/animals/penguin.mp3'),
(1, 'Frog', 'Frogs can jump and are often found near water.', 'uploads/animals/frog.jpg', 'uploads/animals/frog.mp3'),
(1, 'Monkey', 'Monkeys are playful animals that love to swing from trees.', 'uploads/animals/monkey.jpg', 'uploads/animals/monkey.mp3'),
(1, 'Tiger', 'Tigers have orange fur with black stripes and are very fast runners.', 'uploads/animals/tiger.jpg', 'uploads/animals/tiger.mp3');


-- Insert Alphabets
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(2, 'A', 'A is for Apple. A juicy red fruit that grows on trees.', 'uploads/alphabets/a.jpg', 'uploads/alphabets/a.mp3'),
(2, 'B', 'B is for Ball. A round toy that bounces and rolls.', 'uploads/alphabets/b.jpg', 'uploads/alphabets/b.mp3'),
(2, 'C', 'C is for Cat. A furry pet that says meow.', 'uploads/alphabets/c.jpg', 'uploads/alphabets/c.mp3'),
(2, 'D', 'D is for Dog. A friendly pet that barks and wags its tail.', 'uploads/alphabets/d.jpg', 'uploads/alphabets/d.mp3'),
(2, 'E', 'E is for Elephant. A big animal with a long trunk.', 'uploads/alphabets/e.jpg', 'uploads/alphabets/e.mp3'),
(2, 'F', 'F is for Fish. A creature that swims in water.', 'uploads/alphabets/f.jpg', 'uploads/alphabets/f.mp3'),
(2, 'G', 'G is for Giraffe. An animal with a long neck.', 'uploads/alphabets/g.jpg', 'uploads/alphabets/g.mp3'),
(2, 'H', 'H is for House. A place where people live.', 'uploads/alphabets/h.jpg', 'uploads/alphabets/h.mp3'),
(2, 'I', 'I is for Ice Cream. A cold and sweet treat.', 'uploads/alphabets/i.jpg', 'uploads/alphabets/i.mp3'),
(2, 'J', 'J is for Juice. A drink made from fruits.', 'uploads/alphabets/j.jpg', 'uploads/alphabets/j.mp3'),
(2, 'K', 'K is for Kite. A toy that flies in the sky when it is windy.', 'uploads/alphabets/k.jpg', 'uploads/alphabets/k.mp3'),
(2, 'L', 'L is for Lion. A big cat that roars and lives in the jungle.', 'uploads/alphabets/l.jpg', 'uploads/alphabets/l.mp3'),
(2, 'M', 'M is for Monkey. An animal that loves to climb trees.', 'uploads/alphabets/m.jpg', 'uploads/alphabets/m.mp3'),
(2, 'N', 'N is for Nest. A home made by birds.', 'uploads/alphabets/n.jpg', 'uploads/alphabets/n.mp3'),
(2, 'O', 'O is for Orange. A round fruit that is orange in color.', 'uploads/alphabets/o.jpg', 'uploads/alphabets/o.mp3'),
(2, 'P', 'P is for Penguin. A bird that cannot fly but swims well.', 'uploads/alphabets/p.jpg', 'uploads/alphabets/p.mp3'),
(2, 'Q', 'Q is for Queen. A queen that rules a kingdom.', 'uploads/alphabets/q.jpg', 'uploads/alphabets/q.mp3'),
(2, 'R', 'R is for Rabbit. A small animal with long ears.', 'uploads/alphabets/r.jpg', 'uploads/alphabets/r.mp3'),
(2, 'S', 'S is for Sun. The big star that gives us light and warmth.', 'uploads/alphabets/s.jpg', 'uploads/alphabets/s.mp3'),
(2, 'T', 'T is for Tiger. A big cat with stripes.', 'uploads/alphabets/t.jpg', 'uploads/alphabets/t.mp3'),
(2, 'U', 'U is for Umbrella. A tool used to stay dry in the rain.', 'uploads/alphabets/u.jpg', 'uploads/alphabets/u.mp3'),
(2, 'V', 'V is for Violin. A musical instrument played with a bow.', 'uploads/alphabets/v.jpg', 'uploads/alphabets/v.mp3'),
(2, 'W', 'W is for Whale. A large animal that lives in the ocean.', 'uploads/alphabets/w.jpg', 'uploads/alphabets/w.mp3'),
(2, 'X', 'X is for Xylophone. A musical instrument with wooden bars.', 'uploads/alphabets/x.jpg', 'uploads/alphabets/x.mp3'),
(2, 'Y', 'Y is for Yo-Yo. A toy that goes up and down on a string.', 'uploads/alphabets/y.jpg', 'uploads/alphabets/y.mp3'),
(2, 'Z', 'Z is for Zebra. An animal with black and white stripes.', 'uploads/alphabets/z.jpg', 'uploads/alphabets/z.mp3');

-- Insert Transport
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(3, 'Car', 'Cars have four wheels and take us places on roads.', 'uploads/transport/car.jpg', 'uploads/transport/car.mp3'),
(3, 'Bus', 'Buses are big vehicles that carry many people.', 'uploads/transport/bus.jpg', 'uploads/transport/bus.mp3'),
(3, 'Train', 'Trains run on tracks and have many connected cars.', 'uploads/transport/train.jpg', 'uploads/transport/train.mp3'),
(3, 'Airplane', 'Airplanes fly in the sky and have wings.', 'uploads/transport/airplane.jpg', 'uploads/transport/airplane.mp3'),
(3, 'Boat', 'Boats float on water and help us travel across rivers and seas.', 'uploads/transport/boat.jpg', 'uploads/transport/boat.mp3'),
(3, 'Scooter', 'Scooters are small vehicles with two wheels and a platform.', 'uploads/transport/scooter.jpg', 'uploads/transport/scooter.mp3'),
(3, 'Bicycle', 'Bicycles have two wheels and are powered by pedaling.', 'uploads/transport/bicycle.jpg', 'uploads/transport/bicycle.mp3'),
(3, 'Motorcycle', 'Motorcycles are fast vehicles with two wheels.', 'uploads/transport/motorcycle.jpg', 'uploads/transport/motorcycle.mp3'),
(3, 'Truck', 'Trucks are big vehicles that carry heavy loads.', 'uploads/transport/truck.jpg', 'uploads/transport/truck.mp3'),
(3, 'Helicopter', 'Helicopters have blades that spin and can fly straight up.', 'uploads/transport/helicopter.jpg', 'uploads/transport/helicopter.mp3'),
(3, 'Submarine', 'Submarines travel underwater and can explore the ocean.', 'uploads/transport/submarine.jpg', 'uploads/transport/submarine.mp3');

-- Insert Fruits
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(4, 'Apple', 'A sweet, crunchy fruit that is red, green, or yellow.', 'uploads/fruits/apple.jpg', 'uploads/fruits/apple.mp3'),
(4, 'Banana', 'A long, yellow fruit that is soft and sweet.', 'uploads/fruits/banana.jpg', 'uploads/fruits/banana.mp3'),
(4, 'Orange', 'A round, orange fruit that is juicy and full of vitamin C.', 'uploads/fruits/orange.jpg', 'uploads/fruits/orange.mp3'),
(4, 'Grapes', 'Small, round fruits that grow in bunches and can be green or purple.', 'uploads/fruits/grapes.jpg', 'uploads/fruits/grapes.mp3'),
(4, 'Strawberry', 'A small, red fruit with tiny seeds on the outside.', 'uploads/fruits/strawberry.jpg', 'uploads/fruits/strawberry.mp3'),
(4, 'Watermelon', 'A large fruit with a green rind and red, juicy inside.', 'uploads/fruits/watermelon.jpg', 'uploads/fruits/watermelon.mp3'),
(4, 'Pineapple', 'A tropical fruit with a spiky skin and sweet, yellow inside.', 'uploads/fruits/pineapple.jpg', 'uploads/fruits/pineapple.mp3'),
(4, 'Mango', 'A sweet, tropical fruit that is yellow or orange inside.', 'uploads/fruits/mango.jpg', 'uploads/fruits/mango.mp3'),
(4, 'Cherry', 'A small, round fruit that is red or black and very sweet.', 'uploads/fruits/cherry.jpg', 'uploads/fruits/cherry.mp3'),
(4, 'Peach', 'A soft fruit with fuzzy skin that is yellow or pink.', 'uploads/fruits/peach.jpg', 'uploads/fruits/peach.mp3'),
(4, 'Blueberry', 'A small, round fruit that is blue and very sweet.', 'uploads/fruits/blueberry.jpg', 'uploads/fruits/blueberry.mp3'),
(4, 'Kiwi', 'A small, brown fruit with green inside and tiny seeds.', 'uploads/fruits/kiwi.jpg', 'uploads/fruits/kiwi.mp3'),
(4, 'Papaya', 'A tropical fruit that is orange inside and has black seeds.', 'uploads/fruits/papaya.jpg', 'uploads/fruits/papaya.mp3'),
(4, 'Avocado', 'A green fruit that is creamy and often used in salads.', 'uploads/fruits/avocado.jpg', 'uploads/fruits/avocado.mp3'),
(4, 'Lemon', 'A sour yellow fruit that is often used in drinks.', 'uploads/fruits/lemon.jpg', 'uploads/fruits/lemon.mp3'),
(4, 'Coconut', 'A large fruit with a hard shell and sweet water inside.', 'uploads/fruits/coconut.jpg', 'uploads/fruits/coconut.mp3');

-- Insert Colors
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(5, 'Red', 'Red is the color of apples and fire trucks.', 'uploads/colors/red.jpg', 'uploads/colors/red.mp3'),
(5, 'Blue', 'Blue is the color of the sky and the ocean.', 'uploads/colors/blue.jpg', 'uploads/colors/blue.mp3'),
(5, 'Yellow', 'Yellow is the color of the sun and bananas.', 'uploads/colors/yellow.jpg', 'uploads/colors/yellow.mp3'),
(5, 'Green', 'Green is the color of grass and leaves.', 'uploads/colors/green.jpg', 'uploads/colors/green.mp3'),
(5, 'Purple', 'Purple is the color of grapes and some flowers.', 'uploads/colors/purple.jpg', 'uploads/colors/purple.mp3'),
(5, 'Orange', 'Orange is the color of oranges and pumpkins.', 'uploads/colors/orange.jpg', 'uploads/colors/orange.mp3'),
(5, 'Pink', 'Pink is the color of cotton candy and some flowers.', 'uploads/colors/pink.jpg', 'uploads/colors/pink.mp3'),
(5, 'Brown', 'Brown is the color of chocolate and tree trunks.', 'uploads/colors/brown.jpg', 'uploads/colors/brown.mp3'),
(5, 'Black', 'Black is the color of night and some animals.', 'uploads/colors/black.jpg', 'uploads/colors/black.mp3'),
(5, 'White', 'White is the color of snow and clouds.', 'uploads/colors/white.jpg', 'uploads/colors/white.mp3'),
(5, 'Gray', 'Gray is the color of stones and some animals.', 'uploads/colors/gray.jpg', 'uploads/colors/gray.mp3'),
(5, 'Gold', 'Gold is a shiny color that looks like metal.', 'uploads/colors/gold.jpg', 'uploads/colors/gold.mp3'),
(5, 'Silver', 'Silver is a shiny color that looks like metal.', 'uploads/colors/silver.jpg', 'uploads/colors/silver.mp3');

-- Insert Shapes
INSERT INTO elements (category_id, name, description, image_path, audio_path) VALUES
(6, 'Circle', 'A circle is round like a ball or a wheel.', 'uploads/shapes/circle.jpg', 'uploads/shapes/circle.mp3'),
(6, 'Square', 'A square has four equal sides and four corners.', 'uploads/shapes/square.jpg', 'uploads/shapes/square.mp3'),
(6, 'Triangle', 'A triangle has three sides and three corners.', 'uploads/shapes/triangle.jpg', 'uploads/shapes/triangle.mp3'),
(6, 'Rectangle', 'A rectangle has four sides and four corners, with opposite sides equal.', 'uploads/shapes/rectangle.jpg', 'uploads/shapes/rectangle.mp3'),
(6, 'Star', 'A star has five points and looks like the stars in the sky.', 'uploads/shapes/star.jpg', 'uploads/shapes/star.mp3'),
(6, 'Heart', 'A heart shape is often used to show love.', 'uploads/shapes/heart.jpg', 'uploads/shapes/heart.mp3'),
(6, 'Oval', 'An oval is like a stretched circle.', 'uploads/shapes/oval.jpg', 'uploads/shapes/oval.mp3'),
(6, 'Diamond', 'A diamond shape looks like a square that is tilted.', 'uploads/shapes/diamond.jpg', 'uploads/shapes/diamond.mp3'),
(6, 'Pentagon', 'A pentagon has five sides and five corners.', 'uploads/shapes/pentagon.jpg', 'uploads/shapes/pentagon.mp3'),
(6, 'Hexagon', 'A hexagon has six sides and six corners.', 'uploads/shapes/hexagon.jpg', 'uploads/shapes/hexagon.mp3'); 