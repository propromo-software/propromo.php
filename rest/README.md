# Elysia with Bun runtime

## Getting Started
To get started with this template, simply paste this command into your terminal:
```bash
bun create elysia ./elysia-example
```

## Development
To start the development server run:
```bash
bun run dev
```

Open http://localhost:3000/ with your browser to see the result.

## Production

### Deployment

```bash
# build and push the image in . to heroku
heroku container:push web
```

```bash
# deploy the container to heroku using the pushed image
heroku container:release web
```
