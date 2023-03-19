"""
A GUI-based zombie survival game wherein the player has to reach the hospital
whilst evading zombies.

Task 1: Basic Gameplay
Task 1 requires you to implement a functional GUI-based version of EndOfDayz.
There are three major sections to the GUI; a heading label at the top, the game
map (bottom left), and the inventory (bottom right).
"""

# Replace these <strings> with your name, student number and email address.
__author__ = "<Yi-Ning Ho>, <45970319>"
__email__ = "<s4597031@student.uq.edu.au>"


from typing import Tuple
from a2_solution import Position, Game, advanced_game, first_in_direction
import tkinter as tk
from tkinter.messagebox import askyesno
from constants import *


def get_text_colour(background_colour):
    """
    Get the text colour depends on the background colour.
    """
    if background_colour == '#371D33':
        return 'white'  #RRGGBB
    else:
        return 'black'
    

class AbstractGrid(tk.Canvas):
    """
    AbstractGrid is an abstract view class which inherits from tk.Canvas and
    provides base functionality for other view classes. An AbstractGrid can be
    thought of as a grid with a set number of rows and columns, which supports
    creation of text at specific positions based on row and column. The number
    of rows may differ from the number of columns, and the cells may be non-square.
    """

    def __init__(self, master, rows, cols, width, height, **kwargs):
        """
        Parameters:
            master (Tk): This is the window that our GUI is created in.
            rows (int): The number of rows in the grid.
            cols (int): The number of columns in the grid.
            width (int): The width of the grid (in pixels).
            height (int): The height of the grid (in pixels).
            **kwargs: It signifies that any additional named arguments supported
                      by tk.Canvas should also be supported by AbstractGrid.
        """
        super().__init__(master=master, width=width, height=height, **kwargs)
        self._master = master
        self._rows = rows
        self._cols = cols
        self._width = width
        self._height = height
        self._cellwidth = width / cols
        self._cellheight = height / rows

    def get_bbox(self, position: Position) -> Tuple[float, float, float, float]:
        """
        Returns the bounding box for the position.

        Parameters:
            position (Position): Position(x, y) position.

        Returns:
            A tuple containing information about the pixel positions of the
            edges of the shape, in the form (x min, y min, x max, y max).
        """
        col = position.get_x()
        row = position.get_y()
        x_min = col * self._cellwidth
        y_min = row * self._cellheight
        x_max = (col + 1) * self._cellwidth
        y_max = (row + 1) * self._cellheight
        bounding_box = (x_min, y_min, x_max, y_max)
        return bounding_box

    def pixel_to_position(self, pixel: Tuple[int, int]) -> Position:
        """
        Converts the (x, y) pixel position (in graphics units) to a position.

        Parameters:
            pixel (tuple): (x, y) pixel position.
            
        Returns:
            The Position where the (x, y) pixel position is in.
        """
        x, y = pixel
        row = y // self._cellheight
        col = x // self._cellwidth
        position = Position(col, row)
        return position

    def get_position_center(self, position: Position) -> Tuple[float, float]:
        """
        Gets the graphics coordinates for the center of the cell at the given
        position.

        Parameters:
            position (Position): Position(x, y) position.

        Returns:
            A tuple containing information about the pixel position of the
            center of the cell, in the form (x, y).
        """
        x_min, y_min, x_max, y_max = self.get_bbox(position)
        center_x = (x_min + x_max)/2
        center_y = (y_min + y_max)/2
        center = (center_x, center_y)
        return center

    def annotate_position(self, position: Position, text: str, **kwarg) -> None:
        """
        Annotates the center of the cell at the given position with the provided
        text.

        Parameters: 
            position (Position): Position(x, y) position.
            text (str): The string of this position instance.
        """
        center_x, center_y = self.get_position_center(position)
        self.create_text(center_x, center_y, text=text, **kwarg)


class BasicMap(AbstractGrid):
    """
    BasicMap is a view class which inherits from AbstractGrid. Entities are drawn
    on the map using coloured rectangles at different (row, column) positions.
    Annotate the rectangles of all entities with what they represent by using
    the create_rectangle and create_text methods from tk.Canvas.
    The colours representing each entity are:
        • Zombies: Light green (#B8D58E)
        • Pickups: Light purple (#E5E1EF)
        • Player and Hospital: Dark purple with white text (#371D33)
        • Background: Light brown (#B5B28F)
    Your program should work for reasonable map sizes, and you may assume that
    the map will always be square (i.e. rows = columns). Each rectangle should
    be 50 pixels high and 50 pixels wide. You should set the background colour
    of the BasicMap instance by using the kwargs.
    """
    
    def __init__(self, master, size, **kwargs):
        """
        Parameters:
            master (Tk): This is the window that our GUI is created in.
            size (int): As the map will always be square, it should be the number
                        of rows (= the number of columns) in the grid.
        """
        super().__init__(master=master, rows=size, cols=size, \
                         width=size*CELL_SIZE, height=size*CELL_SIZE, **kwargs)

    def draw_entity(self, position: tuple, tile_type: str) -> None:
        """
        Draws the entity with tile type at the given position using a coloured
        rectangle with superimposed text identifying the entity.

        Parameters:
            position (tuple): (x, y) position.
            tile_type (str): The display character of the entity.
                             (i.e. "P", "H", "Z", "T", "G", "C")
        """
        x_min, y_min, x_max, y_max = self.get_bbox(position)
        entity_background = ENTITY_COLOURS[tile_type]
        self.create_rectangle(x_min, y_min, x_max, y_max, fill=entity_background)
        text_colour = get_text_colour(entity_background)
        self.annotate_position(position, tile_type, fill=text_colour)

    def draw_entities(self, items: dict):
        """
        Draw all the entities in the map.

        Parameters:
            items (dict): A dictionary with position instances as the keys and
                          entity instances as the values.
        """
        for position, entity in items:
            self.draw_entity(position, entity.display())


class InventoryView(AbstractGrid):
    """
    InventoryView is a view class which inherits from AbstractGrid and displays
    the items the player has in their inventory. This class also provides a
    mechanism through which the user can 'activate' or 'deactivate' an item held
    in the player's inventory. When a player picks up an item it is added to the
    inventory and displayed in the next free row in the inventory view, along
    with its maximum lifetime.

    When the user left clicks on the InventoryView in the row displaying that item,
    the item is:
    • 'Activated' if the item was not already activated and no other item is
        currently active. Only one item may be active at any given time.
        Once activated, the row of the inventory view should be highlighted, the
        lifetime should begin to decrease by 1 every game step (i.e. every second),
        and this change in lifetime should be reflected in the inventory view.
    • 'Deactivated' if the item was activated. The item should no longer be
        highlighted, the lifetime countdown should stop, and the effects on the
        player should cease. A deactivated item can be reactivated, but the
        lifetime starts from where it left off.

    Once the lifetime reaches 0, the item is no longer applied to the player and
    is removed from the InventoryView. Any items below the newly expired item
    should move up one row to avoid any empty space between items.
    """
    
    def __init__(self, master, rows, **kwargs):
        """
        Parameters:
            master (Tk): This is the window that our GUI is created in.
            rows (int): The number of rows in the InventoryView.
        """
        super().__init__(master=master, rows=rows, cols=2, \
                         width=INVENTORY_WIDTH, height=rows*CELL_SIZE, **kwargs)

    def draw(self, inventory: 'Inventory') -> None:
        """
        Draw the inventory label and current items with their remaining lifetimes.

        Parameters:
            inventory (Inventory): Current items and their remaining lifetimes.
        """
        def get_row_bbox(row):
            """
            Get the bounding box for each item in the InventoryView.
            +-------------------------------+
            |  inventory item  |  lifetime  |
            +-------------------------------+
            """
            x_min, y_min = self.get_bbox(Position(0, row))[:2]
            x_max, y_max = self.get_bbox(Position(1, row))[2:]
            return (x_min, y_min, x_max, y_max)

        self.delete("all")

        # Print the inventory label.
        self.create_text(100, 25, text="Inventory", \
                               fill=DARK_PURPLE, font=('Arial',16))

        # Print the current items with their remaining lifetimes.
        for row, item in enumerate(inventory.get_items()):
            row += 1
            if item.is_active():
                item_background = ACCENT_COLOUR
            else:
                item_background = LIGHT_PURPLE
            text_colour = get_text_colour(item_background)
            self.create_rectangle(*get_row_bbox(row), fill=item_background, width=0)
            self.annotate_position(Position(0, row), type(item).__name__, \
                                   fill=text_colour, font=('microsoft yahei',12))
            self.annotate_position(Position(1, row), item.get_lifetime(), \
                                   fill=text_colour, font=('microsoft yahei',12))

    def toggle_item_activation(self, pixel: tuple, inventory: 'Inventory'):
        """
        Activates or deactivates the item (if one exists) in the row containing
        the pixel.

        Parameters:
            pixel (tuple): (x, y) pixel position where the user left clicks.
            inventory (Inventory): Current items and their remaining lifetimes.
        """
        item_list = inventory.get_items()
        index = int(self.pixel_to_position(pixel).get_y()) - 1
        if index >= 0 and index < len(item_list):
            if item_list[index].is_active() or (not inventory.any_active()):
                item_list[index].toggle_active()
                self.draw(inventory)

class BasicGraphicalInterface:
    """
    The BasicGraphicalInterface should manage the overall view
    (i.e. constructing the three major widgets) and event handling.
    """
    # Set _handling_end game False at the beginning of the game.
    _handling_end = False
    # Set _step_id None at the beginning of the game.
    _step_id = None

    def __init__(self, root, size):
        """
        Parameters:
            root (tk): Represents the root window.
            size (int): Represents the number of rows (= number of columns) in
                        the game map.
        """
        self._root = root
        frame_1 = tk.Frame(root)
        self._heading = tk.Label(frame_1, bg=DARK_PURPLE, \
                                 fg='white', text=TITLE, font=('Arial',20))
        self._heading.pack(anchor=tk.CENTER, fill=tk.BOTH)
        frame_2 = tk.Frame(root)
        self._map = BasicMap(frame_2, size, bg=MAP_BACKGROUND_COLOUR)
        self._inventory_view = InventoryView(frame_2, size, bg=LIGHT_PURPLE)
        self._map.pack(side=tk.LEFT, padx=5, pady=5)
        self._inventory_view.pack(side=tk.LEFT, padx=5, pady=5)
        frame_1.pack(fill=tk.BOTH)
        frame_2.pack(fill=tk.BOTH)
    
    def _inventory_click(self, event, inventory: 'Inventory'):
        """
        This method should be called when the user left clicks on inventory
        view. It must handle activating or deactivating the clicked item
        (if one exists) and update both the model and the view accordingly.

        Parameters:
            event:
            inventory (Inventory): Current items and their remaining lifetimes.
        """
        pixel = (event.x, event.y)
        self._inventory_view.toggle_item_activation(pixel, inventory)

    def draw(self, game: Game):
        """
        Clears and redraws the view based on the current game state.

        Parameters:
            game (Game): The Game which the user is playing.
        """
        self._map.delete("all")
##        for position, entity in game.get_grid().get_mapping().items():
##            self._map.draw_entity(position, entity.display())

        self._map.draw_entities(game.get_grid().get_mapping().items())
        self._inventory_view.draw(game.get_player().get_inventory())
        self._handle_end_game(game)

    def _move(self, game: Game, direction: str):
        """
        Handles moving the player and redrawing the game. It may be easiest to
        create a new method to handle the `<KeyPress>' event, which calls move
        with the relevant arguments.

        Parameters:
            game (Game): The Game which the user is playing.
            direction (str): Character representing the direction in which the
                             player should be moved.
        """
        offset = game.direction_to_offset(direction)
        if offset is not None:
            game.move_player(offset)

        self.draw(game)

    def _request_next_step(self):
        """
        A recursion of requesting next step if self._step_id == None.
        """
        if self._step_id == None:
            self._step_id = self._root.after(1000, self._step, self._game)

    def _stop_step(self):
        """
        To control when to stop stop next step.
        (i.e. at the end of the game or restart a new game.)
        """
        if self._step_id != None:
            self._root.after_cancel(self._step_id)
            self._step_id = None

    def _step(self, game: Game):
        """
        The step method is called every second. This method triggers the step
        method for the game and updates the view accordingly.

        Parameters:
            game (Game): The Game which the user is playing.
        """
        self._step_id = None
        game.step()
        self.draw(game)

        self._request_next_step()

    def restart_game(self):
        """
        Restart the game if the user choose to restart.
        """
        self._stop_step()
        new_game = advanced_game(MAP_FILE)
        self._game.__init__(new_game.get_grid())
        
        self.draw(self._game)
        self._request_next_step()

    def _handle_end_game(self, game: Game):
        """
        Handle the end of the game.

        Parameters:
            game (Game): The Game which the user is playing.
        """
        def ask_replay(msg: str):
            """
            Ask the user whether he/she wants to restart the game.

            Parameters:
                msg (str): Title of the messagebox (You won / You lost)
            """ 
            self._stop_step()
            if askyesno(msg, "Do you want to restart?"):
                self.restart_game()
            else:
                exit()
                
        if not self._handling_end:
            self._handling_end = True
            if game.has_won():
                ask_replay("You won")
            elif game.has_lost():
                ask_replay("You lost")
            self._handling_end = False

    def play(self, game: Game):
        """
        Binds events and initialises gameplay.
        Parameters:
            game (Game): The Game which the user is playing.
        """
        def _key_callback(event):
            """
            Manage the player action with 'W', 'A', 'S', 'D'.
            """
            self._move(game, event.char.upper())
            
        def _fire_callback(event):
            """
            Manage the fire action with up↑, down↓, left←, or right→ arrows
            on the keyboard.
            """
            if game.get_player().get_inventory().has_active(CROSSBOW):
                direction = ARROWS_TO_DIRECTIONS[event.keysym]
                start = game.get_grid().find_player()
                offset = game.direction_to_offset(direction)
                assert((start is not None) and (offset is not None))
                first = first_in_direction(game.get_grid(), start, offset)
                if first is not None and first[1].display() in ZOMBIES:
                    position, entity = first
                    game.get_grid().remove_entity(position)

        def _mouse_callback(event):
            """
            Manage the left click on inventory view
            """
            self._inventory_click(event, game.get_player().get_inventory())

        self._game = game
        self._map.focus_set()
        self._map.bind("<w>", _key_callback)
        self._map.bind("<a>", _key_callback)
        self._map.bind("<s>", _key_callback)
        self._map.bind("<d>", _key_callback)
        self._map.bind("<Up>", _fire_callback)
        self._map.bind("<Down>", _fire_callback)
        self._map.bind("<Left>", _fire_callback)
        self._map.bind("<Right>", _fire_callback)
        self._inventory_view.bind("<Button-1>", _mouse_callback)

        self._request_next_step()

        self.draw(game)
        self._root.mainloop()



##def main() -> None:
##    """Entry point to gameplay."""
##    game = advanced_game(MAP_FILE)
##
##    root = tk.Tk()
##    root.title('EndOfDayz')
##    gui = BasicGraphicalInterface
##    app = gui(root, game.get_grid().get_size())
##    app.play(game)
##
##
##if __name__ == '__main__':
##    main()
