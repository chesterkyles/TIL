# Git

## Git Config (For Aliases)

```txt
a = add .
acm = !git add . && git commit -m
rt = restore .
f = fetch
co = checkout
cd = checkout develop
cb = checkout -b
fco = !git fetch && git checkout
br = branch
ci = commit
cm = commit -m
ca = commit --amend
cp = cherry-pick
st = status
sa = stash apply
lone = log --oneline
last = log -1 HEAD
reset = reset --hard HEAD~1
unstage = reset --soft HEAD^
current = rev-parse --abbrev-ref HEAD
plo = pull origin
pld = pull origin develop
plm = pull origin main
plb = pull origin feature/upgrade_bootstrap4_install
plc = !CURRENT=$(git current) && git pull origin $CURRENT
psc = !CURRENT=$(git current) && git push origin $CURRENT
pso = push origin
dev = !git checkout develop && git pull origin develop
main = !git checkout main && git pull origin main
brd = !sh -c 'git branch -l $1/* | xargs git branch -D' -
brdall = !sh -c 'git branch -l | xargs git branch -D' -
```

## Git commands

### Get current branch

```sh
git rev-parse --abbrev-ref HEAD
```

### Get all local branches with prefix

```sh
git branch -l <prefix>/*

# Can delete found branches by:
git branch -l <prefix>/* | xargs git branch -D
```
