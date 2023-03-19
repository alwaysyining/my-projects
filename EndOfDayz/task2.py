"""
A GUI-based zombie survival game wherein the player has to reach the hospital
whilst evading zombies.

Task 2: Images, StatusBar, File Menu, and High Scores
Task 2 requires you to add additional features to enhance the game's look and
functionality.
"""

# Replace these <strings> with your name, student number and email address.
__author__ = "<Yi-Ning Ho>, <45970319>"
__email__ = "<s4597031@student.uq.edu.au>"


from typing import Tuple
from a2_solution import *
import tkinter as tk
from tkinter.messagebox import askyesno
from tkinter import filedialog
from constants import *
from task1 import *
from PIL import Image, ImageTk


class LabelImage(tk.Label):
    """
    LabelImage is an abstract view class which inherits from tk.Label and
    provides base functionality for other view classes. 
    """
    def __init__(self, parent, filename, resize, *arg, **kwarg):
        """
        Parameters:
            parent (Tk): This is the window that our GUI is created in.
            resize (tuple): (width, height) of the image in pixel.
            filename (str): The file name of the image.
            **kwargs: It signifies that any additional named arguments supported
                      by tk.Canvas should also be supported by AbstractGrid.
        """
        if resize:
            img = ImageTk.PhotoImage(Image.open(filename).resize(resize))
        else:
            img = ImageTk.PhotoImage(Image.open(filename))
        super().__init__(parent, image=img, *arg, **kwarg)
        self.image = img

class StatusBar(tk.Frame):
    """
    Add a StatusBar class that inherits from tk.Frame. This frame should include:
    • The chaser and chasee images (see images folder).
    • A game timer displaying the number of minutes and seconds the user has been
       playing the current game.
    • A moves counter, displaying how many moves the player has made in the
       current game.
    • A 'Quit Game' button, which ends the program.
    • A 'Restart Game' button, which allows the user to start the game again.
    This must reset the information on the status bar, as well as setting the map
    back to how it appeared at the start of the game.
    Clicking the 'Restart Game' button after game play is finished should start
    a new game.
    """
    def __init__(self, restart_cb, quit_cb, *arg, **kwarg):
        """
        Parameters:
            restart_cb: To restart the game.
            quit_cb: To quit the game.
            **kwargs: It signifies that any additional named arguments supported
                      by tk.Canvas should also be supported by AbstractGrid.
        """
        super().__init__(*arg, **kwarg)

        LabelImage(self, "images/chaser.png", None).grid(column=0, row=0, rowspan=2)

        self._get_widget(tk.Label, "Timer", 1, 0)
        self.timer_msg = self._get_widget(tk.Label, "", 1, 1)
        self.grid_columnconfigure(1, weight=1)

        self._get_widget(tk.Label, "Moves made", 2, 0)
        self.moves_msg = self._get_widget(tk.Label, "", 2, 1)
        self.grid_columnconfigure(2, weight=1)

        self._restart_button = self._get_widget(tk.Button, "Restart Game", 3, 0, \
                                                command=restart_cb)
        self._quit_button = self._get_widget(tk.Button, "Quit Game", 3, 1, \
                                             command=quit_cb)
        self.grid_columnconfigure(3, weight=1)

        LabelImage(self, "images/chasee.png", None).grid(column=4, row=0, rowspan=2)

        # Set the time has passed 0.
        self.set_seconds(0)
        # Set the moves have made 0.
        self.set_moves(0)

    def _get_widget(self, widget, text: str, column: int, row: int, **kwarg):
        """
        Parameters:
            widget (Tk):
            text (str): The text that shows on the widget.
            column (int): The column position of the widget in the Status Bar.
            row (int): The row position of the widget in the Status Bar.
        """
        ret = widget(self, text=text, **kwarg)
        ret.grid(column=column, row=row)
        return ret

    def set_seconds(self, seconds: int):
        """
        The widget shows how much time has passed in 'XX mins XX seconds'.

        Parameters:
            seconds (int): The time has passed (in seconds).
        """
        self._seconds = seconds
        self.timer_msg['text'] = f"{self._seconds//60} mins {self._seconds%60} seconds"

    def set_moves(self, moves: int):
        """
        The widget shows the number of moves the player have made in the game.

        Parameters:
            moves (int): The number of moves the player have made.
        """
        self._moves = moves
        self.moves_msg['text'] = f"{self._moves} moves"

    def on_tick(self):
        """
        Add 1 second every second.
        """
        self.set_seconds(self._seconds + 1)
        

    def on_move(self):
        """
        Add 1 move after every move.
        """
        self.set_moves(self._moves + 1)

    def get_moves(self) -> int: 
        """
        Return the number of moves the player have made.
        """
        return self._moves

class ImageMap(BasicMap):
    """
    The ImageMap class inherits from BasicMap class. This class should behave
    similarly to BasicMap, except that images should be used to display each
    square rather than rectangles (see the provided images folder).
    """
    def __init__(self, *arg, **kwarg):
        super().__init__(*arg, **kwarg)
        self._images = {}
        for type, filename in IMAGES.items():
            self._images[type] = ImageTk.PhotoImage(Image.open(filename))

    def draw_entities(self, items: dict):
        """
        Draw all the entities in the map.

        Parameters:
            items (dict): A dictionary with position instances as the keys and
                          entity instances as the values.
        """
        # Create the tileable_background image.
        for x in range(self._cols):
            for y in range(self._rows):
                self.create_image(x*self._cellwidth, y*self._cellwidth, \
                                  image=self._images[BACK_GROUND], anchor=tk.NW)
        # Create the entities image.
        for position, entity in items:
            self.create_image(position.get_x()*self._cellwidth, \
                              position.get_y()*self._cellwidth, \
                              image=self._images[entity.display()], anchor=tk.NW)


class ImageGraphicalInterface(BasicGraphicalInterface):
    """
    The ImageGraphicalInterface class inherits from BasicGraphicalInterface class.
    Add a file menu with the following options:
    •Restart Game: Restart the current game to its initial state.
    •Save Game: Prompt the user for the location to save their file and save all
                 necessary information to replicate the current state of the game.
    •Load Game: Prompt the user for the location of the file to load a game from
                 and load the game described in that file.
    •Quit: Prompt the player via a messagebox to ask whether they are sure they
            would like to quit. If no, do nothing. If yes, quit the game (window
            should close and program should terminate).
    •High scores: Selecting this option should create a top level window displaying
                   an ordered leaderboard of the best (i.e. lowest) time achieved by
                   users in the game (up to the top 3). These scores should persist
                   even if the app is run again.
    """
    def __init__(self, root, size):
        """
        Parameters:
            root (tk): Represents the root window.
            size (int): Represents the number of rows (= number of columns) in
                        the game map.
        """
        self._root = root
        
        self._status_bar = StatusBar(self.restart_game, exit, root)
        self._status_bar.pack(fill=tk.X, side=tk.BOTTOM, padx=30)

        self._init_mutable_gui(size)
        
        menubar = tk.Menu(root)

        file_menu = tk.Menu(menubar, tearoff=0)
        file_menu.add_command(label="High Score", command=self.high_score)
        file_menu.add_command(label="Restart Game", command=self.restart_game)
        file_menu.add_command(label="Save Game", command=self.save_game)
        file_menu.add_command(label="Load Game", command=self.load_game)
        file_menu.add_command(label="Quit", command=self.quit)
        menubar.add_cascade(label="File", menu=file_menu)

        root.config(menu=menubar)

        self._handling_end = False
        self._load_high_score()

    def _init_mutable_gui(self, size: int):
        """
        Create view and pack ImageMap and InventoryView.

        Parameters:
            size (int): Represents the number of rows (= number of columns) in
                        the game map.
        """
        self.frame = tk.Frame(self._root)

        # Print the entities and the inventory.
        frame_2 = tk.Frame(self.frame)
        self._map = ImageMap(frame_2, size, bg=MAP_BACKGROUND_COLOUR)
        self._inventory_view = InventoryView(frame_2, size, bg=LIGHT_PURPLE)
        self._map.pack(side=tk.LEFT, padx=5, pady=5)
        self._inventory_view.pack(side=tk.LEFT, padx=5, pady=5)
        frame_2.pack(fill=tk.BOTH, side=tk.BOTTOM)
        self.frame.pack()
        self._root.update()

        # Print the banner.
        frame_1 = tk.Frame(self.frame)
        LabelImage(frame_1, "images/banner.png", \
                   resize=(frame_2.winfo_width(), BANNER_HEIGHT)).pack(anchor=tk.CENTER, fill=tk.BOTH)
        frame_1.pack(fill=tk.BOTH, side=tk.TOP)

    def restart_game(self):
        """
        Restart the current game to its initial state.
        """
        map_size = advanced_game(MAP_FILE).get_grid().get_size()
        self._setup_game(map_size, 0, 0)
        super().restart_game()
        self.play(self._game)

    def save_game(self):
        """
        Save all necessary information to replicate the current state of the game.
        """

        def get_active_item_idx():
            """
            Find out the active inventory item and return the index.
            """
            active_inventory = self._game.get_player().get_inventory().get_items()
            for idx, inventory in enumerate(active_inventory):
                if inventory.is_active():
                    return idx
            return -1
            
        game_info = []
        game_info.append(self._game.get_steps())
        game_info.append(self.get_moves())
        game_info.append(self._game.get_grid().get_size())
        game_info.append(self._game.get_grid().get_mapping())
        game_info.append(self._game.get_player().get_inventory().get_items())
        game_info.append(get_active_item_idx())

        self._stop_step()
        if filename := \
           filedialog.asksaveasfilename(initialdir = ".", title = "Save file", \
                                        defaultextension=".eod", \
                                        filetypes = (("endOfDayz file","*.eod"),("all files","*.*"))):
            with open(filename, 'w') as f:
                f.write(str(game_info))
        self._request_next_step()

    def _setup_game(self, size: int, moves: int, seconds: int):
        """
        Setup the game GUI includind statusbar.
        (i.e. after load game or restart game)

        Parameters:
            size (int): Represents the number of rows (= number of columns) in
                        the game map.
            moves (int): The number of moves the player have made.
            seconds (int): The time has passed (in seconds).
        """
        self.frame.destroy()
        self._init_mutable_gui(size)
        self._status_bar.set_moves(moves)
        self._status_bar.set_seconds(seconds)

    def load_game(self):
        """
        Load the game described in that file.
        """
        self._stop_step()
        if filename := \
           filedialog.askopenfilename(initialdir = ".", title = "Load file", \
                                      filetypes = (("endOfDayz file","*.eod"),("all files","*.*"))):
            with open(filename, 'r') as f:
                seconds, moves, size, mapping, inventory, active_idx = eval(f.read())
                grid = Grid(size)
                for position, entity in mapping.items():
                    grid.add_entity(position, entity)
                game = AdvancedGame(grid)
                if active_idx >= 0:
                    inventory[active_idx].toggle_active()
                for each in inventory:
                    game.get_player().get_inventory().add_item(each)
                self._setup_game(size, moves, seconds)
                self.play(game)
        else:
            self._request_next_step()

    def quit(self):
        """
        Prompt the player via a messagebox to ask whether they are sure they
        would like to quit.
        """
        if askyesno(self._root, "Are you sure to quit?"):
            exit()

    def _step(self, game: Game):
        """
        The step method is called every second. This method triggers the step
        method for the game and updates the view accordingly.

        Parameters:
            game (Game): The Game which the user is playing.
        """
        self._status_bar.on_tick()
        super()._step(game)

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
        self._status_bar.on_move()
        super()._move(game, direction)

    def get_moves(self):
        return self._status_bar.get_moves()
    
    def high_score(self):
        """
        Create the High Scores window.
        """
        def get_time_string(seconds: int):
            """
            Seperate the time into XX mins XX seconds.
            """
            if seconds >= 60:
                return f"{seconds//60}m {seconds%60}s"
            return f"{seconds}s"

        high_score_window = tk.Toplevel(self._root)
        high_score_window.title(f'Top {MAX_ALLOWED_HIGH_SCORES}')
        tk.Label(high_score_window, fg=get_text_colour(DARK_PURPLE), \
                 bg=DARK_PURPLE, text="High Scores", font=('Arial',26)).pack(fill=tk.X)
        try:
            for name, seconds in self._top_scores:
                text = f"{name}: {get_time_string(seconds)}"
                tk.Label(high_score_window, text=text, height=1, width=len(text), \
                         font=('Arial',12)).pack()
        except FileNotFoundError:
            pass
        tk.Button(high_score_window, text="Done", \
                  command=high_score_window.destroy, \
                  font=('Arial',12)).pack()

    def _save_high_score(self):
        """
        Seperate name and time player spent into two lines so name string can
        contain any character.
        """
        with open(HIGH_SCORES_FILE, 'w') as f:
            for name, seconds in self._top_scores:
                f.write(name+"\n")
                f.write(str(seconds)+"\n")

    def _update_top_3(self, name: str, seconds: int):
        """
        Update the information of top 3 high scores

        Parameters:
            name (str): The name of the top score player.
            seconds (int): The time spent of the top score player.
        """
        self._top_scores.append((name, seconds))
        self._top_scores.sort(key=lambda x: x[1])
        self._top_scores = self._top_scores[:MAX_ALLOWED_HIGH_SCORES]

    def _load_high_score(self):
        """
        Open the high score window.
        """
        self._top_scores = []
        try:
            name = ""
            with open(HIGH_SCORES_FILE, 'r') as f:
                for line in f:
                    if name == "":
                        name = line.strip('\n')
                    else:
                        try:
                            self._update_top_3(name, int(line))
                            name = ""
                        except:
                            pass
        except FileNotFoundError:
            pass

    def _handle_end_game(self, game: Game):
        """
        Handle the end of the game.

        Parameters:
            game (Game): The Game which the user is playing.
        """
        def ask_player_name(seconds):
            def keyPress(event):
                """
                Limit characters that the user can input.
                """
                if event.char not in ('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!”#$%&\'()*+, -./:;?@[\]^_`{ | }~'):
                    return 'break'
            def save():
                """
                Save the name and time player spent.
                """
                if name:=textbox.get("1.0", "end").strip('\n'):
                    self._update_top_3(name, seconds)
                    self._save_high_score()
                    return True
                return False
            def on_enter():
                """
                Click enter to save and exit the game.
                """
                if save():
                    exit()
            def on_enter_and_play_again():
                """
                Click enter and play again to save and restart a new game.
                """
                if save():
                    window.destroy()
                    self.restart_game()

            self._stop_step()
            window = tk.Toplevel(self._root)
            window.title('You Win!')
            win_label = tk.Label(window, \
                                 text=f"You won in {seconds//60}m and {seconds%60}s! Enter your name: ")
            win_label.grid(columnspan=2, row=0)
            textbox = tk.Text(window, height=1, width=26)
            textbox.bind('<KeyPress>', keyPress)
            textbox.grid(columnspan=2, row=1)
            textbox.focus_set()
            enter_btn = tk.Button(window, text="Enter", command=on_enter)
            enter_btn.grid(column=0, row=2, sticky=tk.E, pady=5)
            enter_again_btn = tk.Button(window, text="Enter and play again", \
                                        command=on_enter_and_play_again)
            enter_again_btn.grid(column=1, row=2, sticky=tk.W, padx=10, pady=5)
            
        def ask_replay(msg):
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
                if len(self._top_scores) < MAX_ALLOWED_HIGH_SCORES or self._game.get_steps() < self._top_scores[-1][1]:
                    ask_player_name(self._game.get_steps())
                else:
                    ask_replay(WIN_MESSAGE)
            elif game.has_lost():
                ask_replay(LOSE_MESSAGE)
            self._handling_end = False



##def main() -> None:
##    """Entry point to gameplay."""
##    game = advanced_game(MAP_FILE)
##
##    root = tk.Tk()
##    root.title('EndOfDayz')
##    gui = ImageGraphicalInterface
##    app = gui(root, game.get_grid().get_size())
##    app.play(game)
##
##
##if __name__ == '__main__':
##    main()

