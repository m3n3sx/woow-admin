#!/usr/bin/env node
import { spawn } from 'child_process';

const build = spawn('npm', ['run', 'build'], {
  cwd: process.cwd(),
  stdio: 'inherit',
  shell: true
});

build.on('close', (code) => {
  console.log(`Build process exited with code ${code}`);
  process.exit(code);
});
