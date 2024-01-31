# Codyception - Cody Command Creator by Kynlo

Codyception is a web-based tool designed to streamline the process of creating custom commands for the Cody AI by Sourcegraph. This intuitive interface allows users to define command names, prompts, and context options with ease, and even select from pre-made commands crafted by Kynlo.

## User Manual
- [Codyception User Manual](https://github.com/Kynlos/Codyception/blob/main/USER-GUIDE.md)

## Features

- **Custom Command Creation**: Users can create unique commands for Cody AI with specific names, prompts, and context options.
- **Pre-made Commands**: A selection of pre-made commands designed by Kynlo to get users started quickly.
- **Custom Command Browser**: View and search all the custom commands easily.
- **Dark Mode**: A toggleable dark mode for a comfortable coding experience in different lighting conditions.
- **Responsive Design**: The website is fully responsive, ensuring a seamless experience on both desktop and mobile devices.
- **Sidebar Navigation**: Quick access to related resources such as Discord, GitHub, Sourcegraph, Docs, and the Sourcegraph Blog.

## Getting Started

To get started with Codyception, simply clone the repository and open `index.html` in your web browser.

```bash
git clone https://github.com/your-username/codyception.git
cd codyception
# Open the index.html file in your default web browser (see below for info on how to run this locally and not on a web-server)
```
### Running Locally

**Note:** You will need to run a basic web-server if you are running this locally.  To do this, you can use any of the following methods:

#### With npm

```bash
npm i http-server -g
cd src/
http-server
```

#### With Python

Create a new file called `cody-srv.py` (this needs to be in the same folder as your `index.html` file), and paste the following code:

```python
import http.server
import socketserver

port = 8000

handler = http.server.SimpleHTTPRequestHandler

with socketserver.TCPServer(("", port), handler) as httpd:
    print(f"Serving on port {port}")

    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("\nServer stopped by user.")
```
Open a terminal window and navigate to the folder with this script, type `python cody-srv.py` and then navigate to `127.0.0.1:8000` - This will now give you access to all the javascript within the site.


## Usage

**Enter Command Details:** Fill in the command name and prompt in the provided input fields.

**Select Context Options:** Choose the context in which your command should operate by selecting from the available options.

**Create Command:** Click the "Create Command" button to generate a .json file with your command details.

**Use Pre-made Commands:** Optionally, select a pre-made command from the dropdown to autofill the form.

## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are greatly appreciated.

## Fork the Project

Contributions are the backbone of the open-source community, turning it into an inspiring space for learning and collaboration. Your contributions are immensely valuable, and we appreciate your effort in making this project better.

To contribute, follow these steps:

1. **Fork the Project:**
   - Click on the "Fork" button at the top-right corner of the repository page.
   - This action will create a copy of the project in your GitHub account.

2. **Create your Feature Branch:**
   - Open your terminal and create a new branch for your feature using the command:
     ```bash
     git checkout -b feature/AmazingFeature
     ```
   - Replace "AmazingFeature" with a brief, descriptive name for your feature.

3. **Commit your Changes:**
   - Make your changes to the codebase.
   - Commit your changes with a clear and concise message:
     ```bash
     git commit -m 'Add some AmazingFeature'
     ```

4. **Push to the Branch:**
   - Push your changes to the branch you created:
     ```bash
     git push origin feature/AmazingFeature
     ```

5. **Open a Pull Request:**
   - Navigate to the original repository.
   - In the "Pull Requests" tab, click on "New Pull Request."
   - Provide a summary of your changes, including any relevant details.
   - Submit the Pull Request, and we'll review your contribution.

Thank you for contributing to our project! 🚀

## License
Distributed under the MIT License. See LICENSE for more information.

## Contact

Kynlo - @Kynlos

Project Link: https://github.com/Kynlos/Codyception

## Acknowledgements

- [Sourcegraph](https://www.sourcegraph.com/)
- [Font Awesome](https://fontawesome.com)
- [Google Fonts](https://fonts.google.com/)



--Written by Cody (GPT4-Turbo-Preview)
